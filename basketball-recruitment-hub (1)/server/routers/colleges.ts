import { z } from "zod";
import { publicProcedure, protectedProcedure, router } from "../_core/trpc";
import { searchColleges, getCollegeById, getCoachesByCollegeId } from "../db";

const collegeFiltersSchema = z.object({
  division: z.string().optional(),
  state: z.string().optional(),
  search: z.string().optional(),
});

export const collegesRouter = router({
  search: publicProcedure
    .input(collegeFiltersSchema)
    .query(async ({ input }) => {
      return searchColleges(input);
    }),

  getById: publicProcedure
    .input(z.number())
    .query(async ({ input }) => {
      return getCollegeById(input);
    }),

  getCoaches: publicProcedure
    .input(z.number())
    .query(async ({ input }) => {
      return getCoachesByCollegeId(input);
    }),

  list: publicProcedure.query(async () => {
    return searchColleges({});
  }),
});
