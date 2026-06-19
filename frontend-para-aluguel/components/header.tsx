"use client"

import { motion } from "framer-motion"
import { useTheme } from "next-themes"
import { Sun, Moon, Cpu, LogIn } from "lucide-react"
import { Button } from "@/components/ui/button"
import Link from "next/link"
import { useEffect, useState } from "react"

export function Header() {
  const { theme, setTheme } = useTheme()
  const [mounted, setMounted] = useState(false)

  useEffect(() => {
    setMounted(true)
  }, [])

  return (
    <motion.header
      initial={{ y: -100, opacity: 0 }}
      animate={{ y: 0, opacity: 1 }}
      transition={{ duration: 0.5, ease: "easeOut" }}
      className="sticky top-0 z-50 w-full border-b border-border/40 bg-background/80 backdrop-blur-xl supports-[backdrop-filter]:bg-background/60"
    >
      <div className="container mx-auto px-4">
        <div className="flex h-16 items-center justify-between">
          <motion.div 
            className="flex items-center gap-2"
            whileHover={{ scale: 1.02 }}
            transition={{ type: "spring", stiffness: 400, damping: 10 }}
          >
            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground">
              <Cpu className="h-5 w-5" />
            </div>
            <div className="flex flex-col">
              <span className="text-lg font-bold leading-none">ConectaFácil</span>
              <span className="text-xs text-muted-foreground">Locações Tecnológicas</span>
            </div>
          </motion.div>

          <div className="flex items-center gap-2">
            {mounted && (
              <motion.div
                initial={{ opacity: 0, scale: 0.8 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ delay: 0.2 }}
              >
                <Button
                  variant="ghost"
                  size="icon"
                  onClick={() => setTheme(theme === "dark" ? "light" : "dark")}
                  className="relative overflow-hidden rounded-full"
                  aria-label="Alternar tema"
                >
                  <motion.div
                    initial={false}
                    animate={{
                      rotate: theme === "dark" ? 180 : 0,
                      scale: theme === "dark" ? 0 : 1,
                    }}
                    transition={{ duration: 0.3 }}
                    className="absolute"
                  >
                    <Sun className="h-5 w-5" />
                  </motion.div>
                  <motion.div
                    initial={false}
                    animate={{
                      rotate: theme === "dark" ? 0 : -180,
                      scale: theme === "dark" ? 1 : 0,
                    }}
                    transition={{ duration: 0.3 }}
                    className="absolute"
                  >
                    <Moon className="h-5 w-5" />
                  </motion.div>
                </Button>
              </motion.div>
            )}

            <motion.div
              initial={{ opacity: 0, x: 20 }}
              animate={{ opacity: 1, x: 0 }}
              transition={{ delay: 0.3 }}
            >
              <Button asChild variant="outline" className="gap-2">
                <Link href="/admin">
                  <LogIn className="h-4 w-4" />
                  <span className="hidden sm:inline">Área Administrativa</span>
                  <span className="sm:hidden">Admin</span>
                </Link>
              </Button>
            </motion.div>
          </div>
        </div>
      </div>
    </motion.header>
  )
}
