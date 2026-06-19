import { protectedProcedure, router } from "../_core/trpc";
import { getDb, getAthleteByUserId, getCampaignsByAthleteId, getRecipientsByCampaignId } from "../db";
import { emailOpens } from "../../drizzle/schema";
import { eq } from "drizzle-orm";

export const dashboardRouter = router({
  getStats: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return { sent: 0, opened: 0, replied: 0, openRate: 0 };

    const campaigns = await getCampaignsByAthleteId(athlete.id);
    
    let sent = 0, opened = 0, replied = 0;
    
    for (const campaign of campaigns) {
      const recipients = await getRecipientsByCampaignId(campaign.id);
      sent += recipients.filter(r => r.status !== "pending").length;
      opened += recipients.filter(r => r.status === "opened" || r.status === "replied").length;
      replied += recipients.filter(r => r.status === "replied").length;
    }

    const openRate = sent > 0 ? Math.round((opened / sent) * 100) : 0;

    return { sent, opened, replied, openRate };
  }),

  getRecentCampaigns: protectedProcedure.query(async ({ ctx }) => {
    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return [];

    const campaigns = await getCampaignsByAthleteId(athlete.id);
    return campaigns.slice(0, 10);
  }),

  getEmailOpenTimeline: protectedProcedure.query(async ({ ctx }) => {
    const db = await getDb();
    if (!db) return [];

    const athlete = await getAthleteByUserId(ctx.user.id);
    if (!athlete) return [];

    const campaigns = await getCampaignsByAthleteId(athlete.id);
    const campaignIds = campaigns.map(c => c.id);

    if (campaignIds.length === 0) return [];

    const opens = await db
      .select()
      .from(emailOpens)
      .limit(100);

    return opens;
  }),
});
