import { decimal, int, json, mysqlEnum, mysqlTable, text, timestamp, varchar, boolean, index } from "drizzle-orm/mysql-core";
import { relations } from "drizzle-orm";

/**
 * Core user table backing auth flow.
 * Extend this file with additional tables as your product grows.
 * Columns use camelCase to match both database fields and generated types.
 */
export const users = mysqlTable("users", {
  /**
   * Surrogate primary key. Auto-incremented numeric value managed by the database.
   * Use this for relations between tables.
   */
  id: int("id").autoincrement().primaryKey(),
  /** Manus OAuth identifier (openId) returned from the OAuth callback. Unique per user. */
  openId: varchar("openId", { length: 64 }).notNull().unique(),
  name: text("name"),
  email: varchar("email", { length: 320 }),
  loginMethod: varchar("loginMethod", { length: 64 }),
  role: mysqlEnum("role", ["user", "admin"]).default("user").notNull(),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
  updatedAt: timestamp("updatedAt").defaultNow().onUpdateNow().notNull(),
  lastSignedIn: timestamp("lastSignedIn").defaultNow().notNull(),
});

export type User = typeof users.$inferSelect;
export type InsertUser = typeof users.$inferInsert;

// Athletes table - Perfil do atleta
export const athletes = mysqlTable("athletes", {
  id: int("id").autoincrement().primaryKey(),
  userId: int("userId").notNull().unique(),
  age: int("age"),
  height: int("height"), // em cm
  position: varchar("position", { length: 10 }), // PG, SG, SF, PF, C
  school: varchar("school", { length: 255 }),
  statistics: json("statistics"), // { PPG, RPG, APG, FG%, 3P%, FT% }
  bio: text("bio"),
  profileImageUrl: varchar("profileImageUrl", { length: 500 }),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
  updatedAt: timestamp("updatedAt").defaultNow().onUpdateNow().notNull(),
}, (table) => ({
  userIdIdx: index("athletes_userId_idx").on(table.userId),
}));

export type Athlete = typeof athletes.$inferSelect;
export type InsertAthlete = typeof athletes.$inferInsert;

// Highlights table - Vídeos de highlights
export const highlights = mysqlTable("highlights", {
  id: int("id").autoincrement().primaryKey(),
  athleteId: int("athleteId").notNull(),
  title: varchar("title", { length: 255 }).notNull(),
  description: text("description"),
  videoUrl: varchar("videoUrl", { length: 500 }).notNull(),
  category: mysqlEnum("category", ["season", "game", "training"]).default("game"),
  duration: int("duration"), // em segundos
  uploadedAt: timestamp("uploadedAt").defaultNow(),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
}, (table) => ({
  athleteIdIdx: index("highlights_athleteId_idx").on(table.athleteId),
}));

export type Highlight = typeof highlights.$inferSelect;
export type InsertHighlight = typeof highlights.$inferInsert;

// Colleges table - Faculdades
export const colleges = mysqlTable("colleges", {
  id: int("id").autoincrement().primaryKey(),
  name: varchar("name", { length: 255 }).notNull(),
  division: mysqlEnum("division", ["NCAA D1", "NCAA D2", "NCAA D3", "NAIA", "JUCO"]).notNull(),
  state: varchar("state", { length: 2 }).notNull(), // Estado (ex: CA, NY)
  city: varchar("city", { length: 100 }),
  website: varchar("website", { length: 500 }),
  latitude: decimal("latitude", { precision: 10, scale: 8 }),
  longitude: decimal("longitude", { precision: 11, scale: 8 }),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
}, (table) => ({
  divisionIdx: index("colleges_division_idx").on(table.division),
  stateIdx: index("colleges_state_idx").on(table.state),
}));

export type College = typeof colleges.$inferSelect;
export type InsertCollege = typeof colleges.$inferInsert;

// Coaches table - Treinadores
export const coaches = mysqlTable("coaches", {
  id: int("id").autoincrement().primaryKey(),
  collegeId: int("collegeId").notNull(),
  firstName: varchar("firstName", { length: 100 }).notNull(),
  lastName: varchar("lastName", { length: 100 }).notNull(),
  email: varchar("email", { length: 320 }).notNull(),
  position: varchar("position", { length: 100 }), // Head Coach, Assistant Coach
  phone: varchar("phone", { length: 20 }),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
}, (table) => ({
  collegeIdIdx: index("coaches_collegeId_idx").on(table.collegeId),
  emailIdx: index("coaches_email_idx").on(table.email),
}));

export type Coach = typeof coaches.$inferSelect;
export type InsertCoach = typeof coaches.$inferInsert;

// Email Templates table
export const emailTemplates = mysqlTable("emailTemplates", {
  id: int("id").autoincrement().primaryKey(),
  userId: int("userId").notNull(),
  name: varchar("name", { length: 255 }).notNull(),
  subject: varchar("subject", { length: 255 }).notNull(),
  body: text("body").notNull(), // HTML com placeholders
  isDefault: boolean("isDefault").default(false),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
  updatedAt: timestamp("updatedAt").defaultNow().onUpdateNow().notNull(),
}, (table) => ({
  userIdIdx: index("emailTemplates_userId_idx").on(table.userId),
}));

export type EmailTemplate = typeof emailTemplates.$inferSelect;
export type InsertEmailTemplate = typeof emailTemplates.$inferInsert;

// Email Campaigns table
export const emailCampaigns = mysqlTable("emailCampaigns", {
  id: int("id").autoincrement().primaryKey(),
  athleteId: int("athleteId").notNull(),
  templateId: int("templateId").notNull(),
  status: mysqlEnum("status", ["draft", "scheduled", "sent", "completed"]).default("draft"),
  scheduledFor: timestamp("scheduledFor"),
  sentAt: timestamp("sentAt"),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
  updatedAt: timestamp("updatedAt").defaultNow().onUpdateNow().notNull(),
}, (table) => ({
  athleteIdIdx: index("emailCampaigns_athleteId_idx").on(table.athleteId),
  statusIdx: index("emailCampaigns_status_idx").on(table.status),
}));

export type EmailCampaign = typeof emailCampaigns.$inferSelect;
export type InsertEmailCampaign = typeof emailCampaigns.$inferInsert;

// Email Recipients table - Rastreamento de envios
export const emailRecipients = mysqlTable("emailRecipients", {
  id: int("id").autoincrement().primaryKey(),
  campaignId: int("campaignId").notNull(),
  coachId: int("coachId").notNull(),
  status: mysqlEnum("status", ["pending", "sent", "opened", "replied", "failed"]).default("pending"),
  sentAt: timestamp("sentAt"),
  openedAt: timestamp("openedAt"),
  repliedAt: timestamp("repliedAt"),
  trackingPixelId: varchar("trackingPixelId", { length: 100 }),
  createdAt: timestamp("createdAt").defaultNow().notNull(),
}, (table) => ({
  campaignIdIdx: index("emailRecipients_campaignId_idx").on(table.campaignId),
  coachIdIdx: index("emailRecipients_coachId_idx").on(table.coachId),
  statusIdx: index("emailRecipients_status_idx").on(table.status),
}));

export type EmailRecipient = typeof emailRecipients.$inferSelect;
export type InsertEmailRecipient = typeof emailRecipients.$inferInsert;

// Email Opens table - Rastreamento de aberturas
export const emailOpens = mysqlTable("emailOpens", {
  id: int("id").autoincrement().primaryKey(),
  recipientId: int("recipientId").notNull(),
  openedAt: timestamp("openedAt").defaultNow().notNull(),
  userAgent: text("userAgent"),
  ipAddress: varchar("ipAddress", { length: 45 }),
}, (table) => ({
  recipientIdIdx: index("emailOpens_recipientId_idx").on(table.recipientId),
}));

export type EmailOpen = typeof emailOpens.$inferSelect;
export type InsertEmailOpen = typeof emailOpens.$inferInsert;

// Recommendations table - Recomendações de faculdades
export const recommendations = mysqlTable("recommendations", {
  id: int("id").autoincrement().primaryKey(),
  athleteId: int("athleteId").notNull(),
  collegeId: int("collegeId").notNull(),
  score: int("score"), // 0-100
  reason: json("reason"), // { criteria: { height, position, statistics, location } }
  createdAt: timestamp("createdAt").defaultNow().notNull(),
}, (table) => ({
  athleteIdIdx: index("recommendations_athleteId_idx").on(table.athleteId),
  collegeIdIdx: index("recommendations_collegeId_idx").on(table.collegeId),
}));

export type Recommendation = typeof recommendations.$inferSelect;
export type InsertRecommendation = typeof recommendations.$inferInsert;