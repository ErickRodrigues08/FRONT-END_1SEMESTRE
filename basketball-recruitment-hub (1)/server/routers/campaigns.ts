import { z } from "zod";
import { protectedProcedure, router } from "../_core/trpc";
import { getDb, getAthleteByUserId, getCampaignsByAthleteId, getRecipientsByCampaignId } from "../db";
import { emailCampaigns } from "../../drizzle/schema";
import { eq } from "drizzle-orm";
import { nanoid } from "nanoid";

const campaignSchema = z.object({
  templateId: z.number(),
  coachIds: z.array(z.number()),
  personalMessage: z.string().optional(),
});

export const campaignsRouter = router({
  list: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return [];
    return getCampaignsByAthleteId(athlete.id);
  }),

  create: protectedProcedure
    .input(campaignSchema)
    .mutation(async ({ ctx, input }) => {
      const db = await getDb();
      if (!db) throw new Error("Database not available");
      
      const athlete = await getAthleteByUserId(ctx.user.id);
      if (!athlete) throw new Error("Athlete profile not found");

      const campaign = await db.insert(emailCampaigns).values({
        athleteId: athlete.id,
        templateId: input.templateId,
        status: "draft",
      });

      return campaign;
    }),

  getStats: protectedProcedure.query(async ({ ctx }) => {
    const db = await getDb();
    if (!db) return { sent: 0, opened: 0, replied: 0 };
    
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return { sent: 0, opened: 0, replied: 0 };

    const campaigns = await getCampaignsByAthleteId(athlete.id);
    
    let sent = 0, opened = 0, replied = 0;
    
    for (const campaign of campaigns) {
      const recipients = await getRecipientsByCampaignId(campaign.id);
      sent += recipients.filter(r => r.status !== "pending").length;
      opened += recipients.filter(r => r.status === "opened" || r.status === "replied").length;
      replied += recipients.filter(r => r.status === "replied").length;
    }

    return { sent, opened, replied };
  }),
});
