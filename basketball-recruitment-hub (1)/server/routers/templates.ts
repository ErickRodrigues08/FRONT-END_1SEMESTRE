import { z } from "zod";
import { protectedProcedure, router } from "../_core/trpc";
import { getDb } from "../db";
import { emailTemplates } from "../../drizzle/schema";
import { eq } from "drizzle-orm";

const templateSchema = z.object({
  name: z.string().min(1).max(255),
  subject: z.string().min(1).max(255),
  body: z.string().min(1),
  isDefault: z.boolean().default(false),
});

export const templatesRouter = router({
  list: protectedProcedure.query(async ({ ctx }) => {
    const db = await getDb();
    if (!db) return [];
    return db.select().from(emailTemplates).where(eq(emailTemplates.userId, ctx.user.id));
  }),

  create: protectedProcedure
    .input(templateSchema)
    .mutation(async ({ ctx, input }) => {
      const db = await getDb();
      if (!db) throw new Error("Database not available");
      
      const result = await db.insert(emailTemplates).values({
        userId: ctx.user.id,
        ...input,
      });
      return result;
    }),

  update: protectedProcedure
    .input(z.object({
      id: z.number(),
      ...templateSchema.shape,
    }))
    .mutation(async ({ ctx, input }) => {
      const db = await getDb();
      if (!db) throw new Error("Database not available");
      
      const { id, ...data } = input;
      await db.update(emailTemplates)
        .set(data)
        .where(eq(emailTemplates.id, id));
      
      return db.select().from(emailTemplates).where(eq(emailTemplates.id, id)).limit(1);
    }),

  delete: protectedProcedure
    .input(z.number())
    .mutation(async ({ ctx, input }) => {
      const db = await getDb();
      if (!db) throw new Error("Database not available");
      
      await db.delete(emailTemplates).where(eq(emailTemplates.id, input));
      return { success: true };
    }),
});
