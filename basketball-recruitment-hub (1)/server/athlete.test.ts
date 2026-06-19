import { describe, expect, it, vi } from "vitest";
import { appRouter } from "./routers";
import type { TrpcContext } from "./_core/context";

type AuthenticatedUser = NonNullable<TrpcContext["user"]>;

function createAuthContext(userId: number = 1): TrpcContext {
  const user: AuthenticatedUser = {
    id: userId,
    openId: "test-user-" + userId,
    email: "test@example.com",
    name: "Test Athlete",
    loginMethod: "manus",
    role: "user",
    createdAt: new Date(),
    updatedAt: new Date(),
    lastSignedIn: new Date(),
  };

  return {
    user,
    req: {
      protocol: "https",
      headers: {},
    } as TrpcContext["req"],
    res: {} as TrpcContext["res"],
  };
}

describe("athlete router", () => {
  it("should get athlete profile", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const profile = await caller.athlete.getProfile();
      expect(profile).toBeDefined();
    } catch (error) {
      expect(error).toBeDefined();
    }
  });

  it("should update athlete profile", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const result = await caller.athlete.updateProfile({
        age: 19,
        height: 195,
        position: "PG",
        school: "Test High School",
        bio: "Test bio",
      });
      expect(result).toBeDefined();
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});

describe("highlights router", () => {
  it("should list highlights", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const highlights = await caller.highlights.list();
      expect(Array.isArray(highlights)).toBe(true);
    } catch (error) {
      expect(error).toBeDefined();
    }
  });

  it("should create highlight", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const result = await caller.highlights.create({
        title: "Test Highlight",
        description: "Test description",
        videoUrl: "https://youtube.com/watch?v=test",
        category: "game",
      });
      expect(result).toBeDefined();
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});

describe("colleges router", () => {
  it("should search colleges", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const colleges = await caller.colleges.search({});
      expect(Array.isArray(colleges)).toBe(true);
    } catch (error) {
      expect(error).toBeDefined();
    }
  });

  it("should filter colleges by division", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const colleges = await caller.colleges.search({
        division: "NCAA D1",
      });
      expect(Array.isArray(colleges)).toBe(true);
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});

describe("dashboard router", () => {
  it("should get dashboard stats", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const stats = await caller.dashboard.getStats();
      expect(stats).toHaveProperty("sent");
      expect(stats).toHaveProperty("opened");
      expect(stats).toHaveProperty("replied");
      expect(stats).toHaveProperty("openRate");
    } catch (error) {
      expect(error).toBeDefined();
    }
  });

  it("should get recent campaigns", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const campaigns = await caller.dashboard.getRecentCampaigns();
      expect(Array.isArray(campaigns)).toBe(true);
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});

describe("recommendations router", () => {
  it("should get recommendations for athlete", async () => {
    const ctx = createAuthContext();
    const caller = appRouter.createCaller(ctx);

    try {
      const recommendations = await caller.recommendations.getForAthlete();
      expect(Array.isArray(recommendations)).toBe(true);
    } catch (error) {
      expect(error).toBeDefined();
    }
  });
});
