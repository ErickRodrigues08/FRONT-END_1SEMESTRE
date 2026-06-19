import { z } from "zod";
import { protectedProcedure, router } from "../_core/trpc";
import { getDb, getAthleteByUserId, getRecommendationsByAthleteId, searchColleges } from "../db";
import { recommendations } from "../../drizzle/schema";
import { desc } from "drizzle-orm";

export async function calculateRecommendations(athleteId: number, userId: number) {
  const db = await getDb();
  if (!db) return [];

  const athlete = await getAthleteByUserId(userId);
  if (!athlete) return [];

  const colleges = await searchColleges({});
  const recommendedColleges = [];

  for (const college of colleges) {
    let score = 0;
    const criteria = {};

    if (athlete.height) {
      const heightDiff = Math.abs(athlete.height - 195);
      if (heightDiff < 10) score += 30;
      else if (heightDiff < 20) score += 20;
      else if (heightDiff < 30) score += 10;
    }

    if (athlete.position) {
      score += 40;
    }

    if (athlete.statistics && typeof athlete.statistics === "object") {
      const stats = athlete.statistics as any;
      if (stats.PPG && stats.PPG > 15) score += 20;
      else if (stats.PPG && stats.PPG > 10) score += 15;
      else if (stats.PPG && stats.PPG > 5) score += 10;
    }

    if (college.division === "NCAA D1") score += 10;
    else if (college.division === "NCAA D2") score += 8;
    else if (college.division === "NCAA D3") score += 6;
    else if (college.division === "NAIA") score += 5;
    else if (college.division === "JUCO") score += 3;

    if (score > 0) {
      recommendedColleges.push({
        collegeId: college.id,
        score,
        reason: criteria,
      });
    }
  }

  recommendedColleges.sort((a, b) => b.score - a.score);

  for (const rec of recommendedColleges.slice(0, 50)) {
    await db.insert(recommendations).values({
      athleteId,
      collegeId: rec.collegeId,
      score: rec.score,
      reason: rec.reason,
    });
  }

  return recommendedColleges.slice(0, 50);
}

export const recommendationsRouter = router({
  getForAthlete: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return [];
    return getRecommendationsByAthleteId(athlete.id);
  }),

  calculateScore: protectedProcedure
    .input(z.number())
    .mutation(async ({ ctx, input: collegeId }) => {
      const athlete = await getAthleteByUserId(ctx.user.id);
      if (!athlete) throw new Error("Athlete not found");

      const db = await getDb();
      if (!db) throw new Error("Database not available");

      let score = 0;
      if (athlete.height) score += 30;
      if (athlete.position) score += 40;
      if (athlete.statistics) score += 20;
      score += 10;

      return { score, collegeId };
    }),

  regenerate: protectedProcedure.mutation(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) throw new Error("Athlete not found");

    const db = await getDb();
    if (!db) throw new Error("Database not available");

    return calculateRecommendations(athlete.id, ctx.user.id);
  }),
});
