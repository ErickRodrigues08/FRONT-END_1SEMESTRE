import { eq, and, inArray, like, desc, asc } from "drizzle-orm";
import { drizzle } from "drizzle-orm/mysql2";
import { InsertUser, users, athletes, highlights, colleges, coaches, emailTemplates, emailCampaigns, emailRecipients, recommendations } from "../drizzle/schema";
import { ENV } from './_core/env';

let _db: ReturnType<typeof drizzle> | null = null;

// Lazily create the drizzle instance so local tooling can run without a DB.
export async function getDb() {
  if (!_db && process.env.DATABASE_URL) {
    try {
      _db = drizzle(process.env.DATABASE_URL);
    } catch (error) {
      console.warn("[Database] Failed to connect:", error);
      _db = null;
    }
  }
  return _db;
}

export async function upsertUser(user: InsertUser): Promise<void> {
  if (!user.openId) {
    throw new Error("User openId is required for upsert");
  }

  const db = await getDb();
  if (!db) {
    console.warn("[Database] Cannot upsert user: database not available");
    return;
  }

  try {
    const values: InsertUser = {
      openId: user.openId,
    };
    const updateSet: Record<string, unknown> = {};

    const textFields = ["name", "email", "loginMethod"] as const;
    type TextField = (typeof textFields)[number];

    const assignNullable = (field: TextField) => {
      const value = user[field];
      if (value === undefined) return;
      const normalized = value ?? null;
      values[field] = normalized;
      updateSet[field] = normalized;
    };

    textFields.forEach(assignNullable);

    if (user.lastSignedIn !== undefined) {
      values.lastSignedIn = user.lastSignedIn;
      updateSet.lastSignedIn = user.lastSignedIn;
    }
    if (user.role !== undefined) {
      values.role = user.role;
      updateSet.role = user.role;
    } else if (user.openId === ENV.ownerOpenId) {
      values.role = 'admin';
      updateSet.role = 'admin';
    }

    if (!values.lastSignedIn) {
      values.lastSignedIn = new Date();
    }

    if (Object.keys(updateSet).length === 0) {
      updateSet.lastSignedIn = new Date();
    }

    await db.insert(users).values(values).onDuplicateKeyUpdate({
      set: updateSet,
    });
  } catch (error) {
    console.error("[Database] Failed to upsert user:", error);
    throw error;
  }
}

export async function getUserByOpenId(openId: string) {
  const db = await getDb();
  if (!db) {
    console.warn("[Database] Cannot get user: database not available");
    return undefined;
  }

  const result = await db.select().from(users).where(eq(users.openId, openId)).limit(1);

  return result.length > 0 ? result[0] : undefined;
}

// Athlete queries
export async function getAthleteByUserId(userId: number) {
  const db = await getDb();
  if (!db) return undefined;
  const result = await db.select().from(athletes).where(eq(athletes.userId, userId)).limit(1);
  return result.length > 0 ? result[0] : undefined;
}

export async function upsertAthlete(data: any) {
  const db = await getDb();
  if (!db) return undefined;
  const existing = await getAthleteByUserId(data.userId);
  if (existing) {
    await db.update(athletes).set(data).where(eq(athletes.userId, data.userId));
    return getAthleteByUserId(data.userId);
  } else {
    const result = await db.insert(athletes).values(data);
    return getAthleteByUserId(data.userId);
  }
}

// Highlights queries
export async function getHighlightsByAthleteId(athleteId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(highlights).where(eq(highlights.athleteId, athleteId)).orderBy(desc(highlights.createdAt));
}

export async function createHighlight(data: any) {
  const db = await getDb();
  if (!db) return undefined;
  const result = await db.insert(highlights).values(data);
  return result;
}

// Colleges queries
export async function searchColleges(filters: { division?: string; state?: string; search?: string }) {
  const db = await getDb();
  if (!db) return [];
  
  const conditions: any[] = [];
  if (filters.division) conditions.push(eq(colleges.division, filters.division as any));
  if (filters.state) conditions.push(eq(colleges.state, filters.state));
  if (filters.search) conditions.push(like(colleges.name, `%${filters.search}%`));
  
  const query = conditions.length > 0 ? db.select().from(colleges).where(and(...conditions)) : db.select().from(colleges);
  return query.orderBy(asc(colleges.name));
}

export async function getCollegeById(collegeId: number) {
  const db = await getDb();
  if (!db) return undefined;
  const result = await db.select().from(colleges).where(eq(colleges.id, collegeId)).limit(1);
  return result.length > 0 ? result[0] : undefined;
}

// Coaches queries
export async function getCoachesByCollegeId(collegeId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(coaches).where(eq(coaches.collegeId, collegeId)).orderBy(asc(coaches.firstName));
}

export async function getCoachById(coachId: number) {
  const db = await getDb();
  if (!db) return undefined;
  const result = await db.select().from(coaches).where(eq(coaches.id, coachId)).limit(1);
  return result.length > 0 ? result[0] : undefined;
}

// Email Templates queries
export async function getEmailTemplatesByUserId(userId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(emailTemplates).where(eq(emailTemplates.userId, userId)).orderBy(desc(emailTemplates.createdAt));
}

export async function getDefaultEmailTemplates() {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(emailTemplates).where(eq(emailTemplates.isDefault, true));
}

// Email Campaigns queries
export async function getCampaignsByAthleteId(athleteId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(emailCampaigns).where(eq(emailCampaigns.athleteId, athleteId)).orderBy(desc(emailCampaigns.createdAt));
}

export async function getCampaignById(campaignId: number) {
  const db = await getDb();
  if (!db) return undefined;
  const result = await db.select().from(emailCampaigns).where(eq(emailCampaigns.id, campaignId)).limit(1);
  return result.length > 0 ? result[0] : undefined;
}

// Email Recipients queries
export async function getRecipientsByCampaignId(campaignId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(emailRecipients).where(eq(emailRecipients.campaignId, campaignId));
}

// Recommendations queries
export async function getRecommendationsByAthleteId(athleteId: number) {
  const db = await getDb();
  if (!db) return [];
  return db.select().from(recommendations).where(eq(recommendations.athleteId, athleteId)).orderBy(desc(recommendations.score));
}
