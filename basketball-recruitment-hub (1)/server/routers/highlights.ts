import { z } from "zod";
import { protectedProcedure, router } from "../_core/trpc";
import { getAthleteByUserId, getHighlightsByAthleteId, createHighlight } from "../db";
import { storagePut } from "../storage";
import { nanoid } from "nanoid";

const highlightSchema = z.object({
  title: z.string().min(1).max(255),
  description: z.string().max(1000).optional(),
  videoUrl: z.string().url(),
  category: z.enum(["season", "game", "training"]).default("game"),
  duration: z.number().int().optional(),
});

export const highlightsRouter = router({
  list: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return [];
    return getHighlightsByAthleteId(athlete.id);
  }),

  create: protectedProcedure
    .input(highlightSchema)
    .mutation(async ({ ctx, input }) => {
      const athlete = await getAthleteByUserId(ctx.user.id);
      if (!athlete) throw new Error("Athlete profile not found");

      const highlight = await createHighlight({
        athleteId: athlete.id,
        ...input,
      });
      return highlight;
    }),

  getUploadUrl: protectedProcedure
    .input(z.object({
      fileName: z.string(),
      contentType: z.string(),
    }))
    .mutation(async ({ ctx, input }) => {
      const athlete = await getAthleteByUserId(ctx.user.id);
      if (!athlete) throw new Error("Athlete profile not found");

      const fileKey = `highlights/${athlete.id}/${nanoid()}-${input.fileName}`;
      
      // Generate presigned URL for upload
      const { url } = await storagePut(fileKey, Buffer.alloc(0), input.contentType);
      
      return {
        uploadUrl: url,
        fileKey,
      };
    }),
});
