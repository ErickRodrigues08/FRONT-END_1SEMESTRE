"use client"

import { useState, useEffect } from "react"
import { motion, AnimatePresence } from "framer-motion"
import { Device, formatCurrency, calculateDays } from "@/lib/devices"
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { CheckCircle2, Loader2, CalendarDays, User, Phone, Calculator } from "lucide-react"

interface RentalModalProps {
  device: Device | null
  open: boolean
  onOpenChange: (open: boolean) => void
}

export function RentalModal({ device, open, onOpenChange }: RentalModalProps) {
  const [formData, setFormData] = useState({
    cliente: "",
    telefone: "",
    data_retirada: "",
    data_prevista: "",
  })
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [isSuccess, setIsSuccess] = useState(false)
  const [totalValue, setTotalValue] = useState(0)

  useEffect(() => {
    if (device && formData.data_retirada && formData.data_prevista) {
      const days = calculateDays(formData.data_retirada, formData.data_prevista)
      setTotalValue(days * device.valor_diaria)
    } else {
      setTotalValue(0)
    }
  }, [formData.data_retirada, formData.data_prevista, device])

  const formatPhone = (value: string) => {
    const numbers = value.replace(/\D/g, "")
    if (numbers.length <= 10) {
      return numbers.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3").trim()
    }
    return numbers.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3").trim()
  }

  const handlePhoneChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const formatted = formatPhone(e.target.value)
    setFormData({ ...formData, telefone: formatted })
  }

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setIsSubmitting(true)
    
    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    setIsSubmitting(false)
    setIsSuccess(true)
    
    setTimeout(() => {
      setIsSuccess(false)
      setFormData({ cliente: "", telefone: "", data_retirada: "", data_prevista: "" })
      onOpenChange(false)
    }, 2500)
  }

  const today = new Date().toISOString().split('T')[0]

  if (!device) return null

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="sm:max-w-md">
        <AnimatePresence mode="wait">
          {isSuccess ? (
            <motion.div
              key="success"
              initial={{ opacity: 0, scale: 0.9 }}
              animate={{ opacity: 1, scale: 1 }}
              exit={{ opacity: 0, scale: 0.9 }}
              className="flex flex-col items-center justify-center py-8"
            >
              <motion.div
                initial={{ scale: 0 }}
                animate={{ scale: 1 }}
                transition={{ 
                  type: "spring",
                  stiffness: 200,
                  damping: 15,
                  delay: 0.1
                }}
              >
                <CheckCircle2 className="h-16 w-16 text-emerald-500" />
              </motion.div>
              <motion.h3
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.3 }}
                className="mt-4 text-xl font-semibold"
              >
                Solicitação Enviada!
              </motion.h3>
              <motion.p
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.4 }}
                className="mt-2 text-center text-muted-foreground"
              >
                Sua solicitação de locação foi registrada. Entraremos em contato em breve.
              </motion.p>
            </motion.div>
          ) : (
            <motion.div
              key="form"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
            >
              <DialogHeader>
                <DialogTitle className="flex items-center gap-2">
                  <CalendarDays className="h-5 w-5 text-primary" />
                  Solicitar Locação
                </DialogTitle>
                <DialogDescription>
                  {device.marca} {device.modelo} - {formatCurrency(device.valor_diaria)}/dia
                </DialogDescription>
              </DialogHeader>

              <form onSubmit={handleSubmit} className="mt-4 space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="cliente" className="flex items-center gap-1.5">
                    <User className="h-3.5 w-3.5" />
                    Nome Completo
                  </Label>
                  <Input
                    id="cliente"
                    placeholder="Seu nome completo"
                    value={formData.cliente}
                    onChange={(e) => setFormData({ ...formData, cliente: e.target.value })}
                    required
                    className="transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="telefone" className="flex items-center gap-1.5">
                    <Phone className="h-3.5 w-3.5" />
                    Telefone
                  </Label>
                  <Input
                    id="telefone"
                    placeholder="(00) 00000-0000"
                    value={formData.telefone}
                    onChange={handlePhoneChange}
                    maxLength={15}
                    required
                    className="transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                  />
                </div>

                <div className="grid grid-cols-2 gap-3">
                  <div className="space-y-2">
                    <Label htmlFor="data_retirada">Data Retirada</Label>
                    <Input
                      id="data_retirada"
                      type="date"
                      min={today}
                      value={formData.data_retirada}
                      onChange={(e) => setFormData({ ...formData, data_retirada: e.target.value })}
                      required
                      className="transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                    />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="data_prevista">Data Devolução</Label>
                    <Input
                      id="data_prevista"
                      type="date"
                      min={formData.data_retirada || today}
                      value={formData.data_prevista}
                      onChange={(e) => setFormData({ ...formData, data_prevista: e.target.value })}
                      required
                      className="transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                    />
                  </div>
                </div>

                {totalValue > 0 && (
                  <motion.div
                    initial={{ opacity: 0, height: 0 }}
                    animate={{ opacity: 1, height: "auto" }}
                    className="rounded-lg border border-primary/20 bg-primary/5 p-3"
                  >
                    <div className="flex items-center justify-between">
                      <span className="flex items-center gap-1.5 text-sm text-muted-foreground">
                        <Calculator className="h-4 w-4" />
                        Valor Estimado
                      </span>
                      <span className="text-lg font-bold text-primary">
                        {formatCurrency(totalValue)}
                      </span>
                    </div>
                    <p className="mt-1 text-xs text-muted-foreground">
                      {calculateDays(formData.data_retirada, formData.data_prevista)} dia(s) × {formatCurrency(device.valor_diaria)}
                    </p>
                  </motion.div>
                )}

                <Button 
                  type="submit" 
                  className="w-full gap-2"
                  disabled={isSubmitting}
                >
                  {isSubmitting ? (
                    <>
                      <Loader2 className="h-4 w-4 animate-spin" />
                      Enviando...
                    </>
                  ) : (
                    <>
                      <CheckCircle2 className="h-4 w-4" />
                      Confirmar Solicitação
                    </>
                  )}
                </Button>
              </form>
            </motion.div>
          )}
        </AnimatePresence>
      </DialogContent>
    </Dialog>
  )
}
