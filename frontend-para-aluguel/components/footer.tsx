"use client"

import { motion } from "framer-motion"
import { Cpu, Mail, Phone, MapPin } from "lucide-react"

export function Footer() {
  return (
    <motion.footer
      initial={{ opacity: 0 }}
      whileInView={{ opacity: 1 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5 }}
      className="border-t border-border bg-card"
    >
      <div className="container mx-auto px-4 py-12">
        <div className="grid gap-8 md:grid-cols-3">
          <div>
            <div className="flex items-center gap-2">
              <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-primary-foreground">
                <Cpu className="h-5 w-5" />
              </div>
              <div>
                <span className="text-lg font-bold">ConectaFácil</span>
                <p className="text-xs text-muted-foreground">Locações Tecnológicas</p>
              </div>
            </div>
            <p className="mt-4 text-sm text-muted-foreground text-pretty">
              Soluções em locação de dispositivos tecnológicos para empresas e profissionais.
            </p>
          </div>

          <div>
            <h3 className="mb-4 font-semibold">Contato</h3>
            <ul className="space-y-3 text-sm text-muted-foreground">
              <li className="flex items-center gap-2">
                <Mail className="h-4 w-4" />
                contato@conectafacil.com
              </li>
              <li className="flex items-center gap-2">
                <Phone className="h-4 w-4" />
                (11) 99999-9999
              </li>
              <li className="flex items-center gap-2">
                <MapPin className="h-4 w-4" />
                São Paulo, SP
              </li>
            </ul>
          </div>

          <div>
            <h3 className="mb-4 font-semibold">Horário de Atendimento</h3>
            <ul className="space-y-2 text-sm text-muted-foreground">
              <li>Segunda a Sexta: 8h às 18h</li>
              <li>Sábado: 9h às 13h</li>
              <li>Domingo: Fechado</li>
            </ul>
          </div>
        </div>

        <div className="mt-8 border-t border-border pt-8 text-center text-sm text-muted-foreground">
          <p>&copy; {new Date().getFullYear()} ConectaFácil. Todos os direitos reservados.</p>
        </div>
      </div>
    </motion.footer>
  )
}
