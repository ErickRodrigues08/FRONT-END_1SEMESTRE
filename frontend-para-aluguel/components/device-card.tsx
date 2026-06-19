"use client"

import { motion } from "framer-motion"
import { Device, formatCurrency } from "@/lib/devices"
import { Card, CardContent, CardFooter, CardHeader } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Laptop, Tablet, CalendarDays, Info } from "lucide-react"
import Image from "next/image"

interface DeviceCardProps {
  device: Device
  index: number
  onRent: (device: Device) => void
}

export function DeviceCard({ device, index, onRent }: DeviceCardProps) {
  const isAvailable = device.status === 'disponivel'
  
  const getCategoryIcon = () => {
    switch (device.categoria) {
      case 'Tablet':
        return <Tablet className="h-3.5 w-3.5" />
      default:
        return <Laptop className="h-3.5 w-3.5" />
    }
  }

  return (
    <motion.div
      initial={{ opacity: 0, y: 30 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ 
        duration: 0.5, 
        delay: index * 0.1,
        ease: [0.25, 0.46, 0.45, 0.94]
      }}
      whileHover={{ y: -8 }}
      className="h-full"
    >
      <Card className="group relative h-full overflow-hidden transition-all duration-300 hover:shadow-xl hover:shadow-primary/10 border-border/50">
        <div className="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-accent/5 opacity-0 transition-opacity duration-300 group-hover:opacity-100" />
        
        <CardHeader className="relative p-0">
          <div className="relative aspect-[4/3] overflow-hidden bg-muted">
            <Image
              src={device.imagem}
              alt={`${device.marca} ${device.modelo}`}
              fill
              className="object-cover transition-transform duration-500 group-hover:scale-110"
              sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent" />
            
            <div className="absolute left-3 top-3 flex gap-2">
              <Badge variant="secondary" className="gap-1 bg-background/90 backdrop-blur-sm">
                {getCategoryIcon()}
                {device.categoria}
              </Badge>
            </div>
            
            <div className="absolute bottom-3 right-3">
              <Badge 
                variant={isAvailable ? "default" : "secondary"}
                className={isAvailable 
                  ? "bg-emerald-500/90 text-white backdrop-blur-sm hover:bg-emerald-500" 
                  : "bg-amber-500/90 text-white backdrop-blur-sm hover:bg-amber-500"
                }
              >
                {isAvailable ? "Disponível" : "Alugado"}
              </Badge>
            </div>
          </div>
        </CardHeader>

        <CardContent className="relative p-4">
          <div className="mb-2 flex items-start justify-between gap-2">
            <div>
              <h3 className="font-semibold text-lg leading-tight text-balance">{device.modelo}</h3>
              <p className="text-sm text-muted-foreground">{device.marca}</p>
            </div>
          </div>
          
          <div className="mb-3 flex items-center gap-1 text-muted-foreground">
            <Info className="h-3.5 w-3.5 flex-shrink-0" />
            <p className="text-xs line-clamp-2">{device.observacoes}</p>
          </div>

          <div className="flex items-baseline gap-1">
            <span className="text-2xl font-bold text-primary">{formatCurrency(device.valor_diaria)}</span>
            <span className="text-sm text-muted-foreground">/dia</span>
          </div>
        </CardContent>

        <CardFooter className="relative p-4 pt-0">
          <Button 
            className="w-full gap-2 transition-all duration-300"
            disabled={!isAvailable}
            onClick={() => onRent(device)}
          >
            <CalendarDays className="h-4 w-4" />
            {isAvailable ? "Alugar Agora" : "Indisponível"}
          </Button>
        </CardFooter>
      </Card>
    </motion.div>
  )
}
