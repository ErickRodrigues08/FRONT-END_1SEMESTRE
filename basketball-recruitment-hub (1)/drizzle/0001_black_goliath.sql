CREATE TABLE `athletes` (
	`id` int AUTO_INCREMENT NOT NULL,
	`userId` int NOT NULL,
	`age` int,
	`height` int,
	`position` varchar(10),
	`school` varchar(255),
	`statistics` json,
	`bio` text,
	`profileImageUrl` varchar(500),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	`updatedAt` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT `athletes_id` PRIMARY KEY(`id`),
	CONSTRAINT `athletes_userId_unique` UNIQUE(`userId`)
);
--> statement-breakpoint
CREATE TABLE `coaches` (
	`id` int AUTO_INCREMENT NOT NULL,
	`collegeId` int NOT NULL,
	`firstName` varchar(100) NOT NULL,
	`lastName` varchar(100) NOT NULL,
	`email` varchar(320) NOT NULL,
	`position` varchar(100),
	`phone` varchar(20),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `coaches_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `colleges` (
	`id` int AUTO_INCREMENT NOT NULL,
	`name` varchar(255) NOT NULL,
	`division` enum('NCAA D1','NCAA D2','NCAA D3','NAIA','JUCO') NOT NULL,
	`state` varchar(2) NOT NULL,
	`city` varchar(100),
	`website` varchar(500),
	`latitude` decimal(10,8),
	`longitude` decimal(11,8),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `colleges_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `emailCampaigns` (
	`id` int AUTO_INCREMENT NOT NULL,
	`athleteId` int NOT NULL,
	`templateId` int NOT NULL,
	`status` enum('draft','scheduled','sent','completed') DEFAULT 'draft',
	`scheduledFor` timestamp,
	`sentAt` timestamp,
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	`updatedAt` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT `emailCampaigns_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `emailOpens` (
	`id` int AUTO_INCREMENT NOT NULL,
	`recipientId` int NOT NULL,
	`openedAt` timestamp NOT NULL DEFAULT (now()),
	`userAgent` text,
	`ipAddress` varchar(45),
	CONSTRAINT `emailOpens_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `emailRecipients` (
	`id` int AUTO_INCREMENT NOT NULL,
	`campaignId` int NOT NULL,
	`coachId` int NOT NULL,
	`status` enum('pending','sent','opened','replied','failed') DEFAULT 'pending',
	`sentAt` timestamp,
	`openedAt` timestamp,
	`repliedAt` timestamp,
	`trackingPixelId` varchar(100),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `emailRecipients_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `emailTemplates` (
	`id` int AUTO_INCREMENT NOT NULL,
	`userId` int NOT NULL,
	`name` varchar(255) NOT NULL,
	`subject` varchar(255) NOT NULL,
	`body` text NOT NULL,
	`isDefault` boolean DEFAULT false,
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	`updatedAt` timestamp NOT NULL DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
	CONSTRAINT `emailTemplates_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `highlights` (
	`id` int AUTO_INCREMENT NOT NULL,
	`athleteId` int NOT NULL,
	`title` varchar(255) NOT NULL,
	`description` text,
	`videoUrl` varchar(500) NOT NULL,
	`category` enum('season','game','training') DEFAULT 'game',
	`duration` int,
	`uploadedAt` timestamp DEFAULT (now()),
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `highlights_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE TABLE `recommendations` (
	`id` int AUTO_INCREMENT NOT NULL,
	`athleteId` int NOT NULL,
	`collegeId` int NOT NULL,
	`score` int,
	`reason` json,
	`createdAt` timestamp NOT NULL DEFAULT (now()),
	CONSTRAINT `recommendations_id` PRIMARY KEY(`id`)
);
--> statement-breakpoint
CREATE INDEX `athletes_userId_idx` ON `athletes` (`userId`);--> statement-breakpoint
CREATE INDEX `coaches_collegeId_idx` ON `coaches` (`collegeId`);--> statement-breakpoint
CREATE INDEX `coaches_email_idx` ON `coaches` (`email`);--> statement-breakpoint
CREATE INDEX `colleges_division_idx` ON `colleges` (`division`);--> statement-breakpoint
CREATE INDEX `colleges_state_idx` ON `colleges` (`state`);--> statement-breakpoint
CREATE INDEX `emailCampaigns_athleteId_idx` ON `emailCampaigns` (`athleteId`);--> statement-breakpoint
CREATE INDEX `emailCampaigns_status_idx` ON `emailCampaigns` (`status`);--> statement-breakpoint
CREATE INDEX `emailOpens_recipientId_idx` ON `emailOpens` (`recipientId`);--> statement-breakpoint
CREATE INDEX `emailRecipients_campaignId_idx` ON `emailRecipients` (`campaignId`);--> statement-breakpoint
CREATE INDEX `emailRecipients_coachId_idx` ON `emailRecipients` (`coachId`);--> statement-breakpoint
CREATE INDEX `emailRecipients_status_idx` ON `emailRecipients` (`status`);--> statement-breakpoint
CREATE INDEX `emailTemplates_userId_idx` ON `emailTemplates` (`userId`);--> statement-breakpoint
CREATE INDEX `highlights_athleteId_idx` ON `highlights` (`athleteId`);--> statement-breakpoint
CREATE INDEX `recommendations_athleteId_idx` ON `recommendations` (`athleteId`);--> statement-breakpoint
CREATE INDEX `recommendations_collegeId_idx` ON `recommendations` (`collegeId`);