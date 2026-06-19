import { z } from "zod";
import { protectedProcedure, router } from "../_core/trpc";
import { getAthleteByUserId, upsertAthlete } from "../db";

const athleteProfileSchema = z.object({
  age: z.number().int().min(16).max(40).optional(),
  height: z.number().int().min(150).max(230).optional(),
  position: z.enum(["PG", "SG", "SF", "PF", "C"]).optional(),
  school: z.string().max(255).optional(),
  statistics: z.record(z.string(), z.any()).optional(),
  bio: z.string().max(2000).optional(),
  profileImageUrl: z.string().url().optional(),
});

export const athleteRouter = router({
  getProfile: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    return athlete || null;
  }),

  updateProfile: protectedProcedure
    .input(athleteProfileSchema)
    .mutation(async ({ ctx, input }) => {
      const updated = await upsertAthlete({
        userId: ctx.user.id,
        ...input,
      });
      return updated;
    }),

  getStatistics: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    return athlete?.statistics || {};
  }),

  updateStatistics: protectedProcedure
    .input(z.record(z.string(), z.any()))
    .mutation(async ({ ctx, input }) => {
      const athlete = await getAthleteByUserId(ctx.user.id);
      if (!athlete) throw new Error("Athlete profile not found");
      
      const updated = await upsertAthlete({
        userId: ctx.user.id,
        statistics: input,
      });
      return updated?.statistics || {};
    }),
});
