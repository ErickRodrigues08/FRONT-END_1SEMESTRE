import { COOKIE_NAME } from "@shared/const";
import { getSessionCookieOptions } from "./_core/cookies";
import { systemRouter } from "./_core/systemRouter";
import { publicProcedure, router } from "./_core/trpc";
import { athleteRouter } from "./routers/athlete";
import { highlightsRouter } from "./routers/highlights";
import { collegesRouter } from "./routers/colleges";
import { templatesRouter } from "./routers/templates";
import { campaignsRouter } from "./routers/campaigns";
import { recommendationsRouter } from "./routers/recommendations";
import { dashboardRouter } from "./routers/dashboard";

export const appRouter = router({
  system: systemRouter,
  auth: router({
    me: publicProcedure.query(opts => opts.ctx.user),
    logout: publicProcedure.mutation(({ ctx }) => {
      const cookieOptions = getSessionCookieOptions(ctx.req);
      ctx.res.clearCookie(COOKIE_NAME, { ...cookieOptions, maxAge: -1 });
      return {
        success: true,
      } as const;
    }),
  }),
  athlete: athleteRouter,
  highlights: highlightsRouter,
  colleges: collegesRouter,
  templates: templatesRouter,
  campaigns: campaignsRouter,
  recommendations: recommendationsRouter,
  dashboard: dashboardRouter,
});

export type AppRouter = typeof appRouter;
