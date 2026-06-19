"use client"

import { useState } from "react"
import { motion } from "framer-motion"
import { Header } from "@/components/header"
import { HeroSection } from "@/components/hero-section"
import { DeviceCard } from "@/components/device-card"
import { RentalModal } from "@/components/rental-modal"
import { Footer } from "@/components/footer"
import { devices, Device } from "@/lib/devices"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Search, Filter, Laptop, Tablet, LayoutGrid } from "lucide-react"

export default function HomePage() {
  const [selectedDevice, setSelectedDevice] = useState<Device | null>(null)
  const [isModalOpen, setIsModalOpen] = useState(false)
  const [searchTerm, setSearchTerm] = useState("")
  const [categoryFilter, setCategoryFilter] = useState<string>("all")
  const [statusFilter, setStatusFilter] = useState<string>("all")

  const filteredDevices = devices.filter((device) => {
    const matchesSearch = 
      device.modelo.toLowerCase().includes(searchTerm.toLowerCase()) ||
      device.marca.toLowerCase().includes(searchTerm.toLowerCase())
    
    const matchesCategory = categoryFilter === "all" || device.categoria === categoryFilter
    const matchesStatus = statusFilter === "all" || device.status === statusFilter

    return matchesSearch && matchesCategory && matchesStatus
  })

  const handleRent = (device: Device) => {
    setSelectedDevice(device)
    setIsModalOpen(true)
  }

  const categories = ["all", "Tablet", "Notebook"]
  const statuses = ["all", "disponivel", "alugado"]

  return (
    <div className="min-h-screen">
      <Header />
      <HeroSection />
      
      <main id="dispositivos" className="container mx-auto px-4 py-12 sm:py-16">
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="mb-8"
        >
          <h2 className="mb-2 text-3xl font-bold sm:text-4xl">Nossos Dispositivos</h2>
          <p className="text-muted-foreground">
            Escolha o dispositivo ideal para sua necessidade
          </p>
        </motion.div>

        {/* Filters */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5, delay: 0.1 }}
          className="mb-8 rounded-xl border border-border bg-card p-4 shadow-sm"
        >
          <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div className="relative flex-1 max-w-md">
              <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
              <Input
                placeholder="Buscar por modelo ou marca..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="pl-10"
              />
            </div>

            <div className="flex flex-wrap items-center gap-2">
              <div className="flex items-center gap-1 text-sm text-muted-foreground">
                <Filter className="h-4 w-4" />
                <span className="hidden sm:inline">Filtros:</span>
              </div>
              
              <div className="flex flex-wrap gap-2">
                {categories.map((cat) => (
                  <Button
                    key={cat}
                    variant={categoryFilter === cat ? "default" : "outline"}
                    size="sm"
                    onClick={() => setCategoryFilter(cat)}
                    className="gap-1.5"
                  >
                    {cat === "all" && <LayoutGrid className="h-3.5 w-3.5" />}
                    {cat === "Tablet" && <Tablet className="h-3.5 w-3.5" />}
                    {cat === "Notebook" && <Laptop className="h-3.5 w-3.5" />}
                    {cat === "all" ? "Todos" : cat}
                  </Button>
                ))}
              </div>

              <div className="h-6 w-px bg-border hidden sm:block" />

              <div className="flex gap-2">
                {statuses.map((status) => (
                  <Badge
                    key={status}
                    variant={statusFilter === status ? "default" : "outline"}
                    className="cursor-pointer transition-colors hover:bg-primary hover:text-primary-foreground"
                    onClick={() => setStatusFilter(status)}
                  >
                    {status === "all" ? "Todos" : status === "disponivel" ? "Disponível" : "Alugado"}
                  </Badge>
                ))}
              </div>
            </div>
          </div>
        </motion.div>

        {/* Device Grid */}
        {filteredDevices.length > 0 ? (
          <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            {filteredDevices.map((device, index) => (
              <DeviceCard
                key={device.id}
                device={device}
                index={index}
                onRent={handleRent}
              />
            ))}
          </div>
        ) : (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            className="flex flex-col items-center justify-center rounded-xl border border-dashed border-border py-16"
          >
            <Search className="mb-4 h-12 w-12 text-muted-foreground/50" />
            <h3 className="mb-2 text-lg font-medium">Nenhum dispositivo encontrado</h3>
            <p className="text-sm text-muted-foreground">
              Tente ajustar os filtros ou buscar por outro termo
            </p>
            <Button
              variant="outline"
              className="mt-4"
              onClick={() => {
                setSearchTerm("")
                setCategoryFilter("all")
                setStatusFilter("all")
              }}
            >
              Limpar Filtros
            </Button>
          </motion.div>
        )}

        {/* Stats Section */}
        <motion.div
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.5 }}
          className="mt-16 grid gap-4 sm:grid-cols-3"
        >
          {[
            { label: "Dispositivos Disponíveis", value: devices.filter(d => d.status === 'disponivel').length },
            { label: "Marcas Parceiras", value: new Set(devices.map(d => d.marca)).size },
            { label: "Clientes Satisfeitos", value: "500+" },
          ].map((stat, index) => (
            <motion.div
              key={stat.label}
              initial={{ opacity: 0, scale: 0.9 }}
              whileInView={{ opacity: 1, scale: 1 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: index * 0.1 }}
              className="rounded-xl border border-border bg-card p-6 text-center shadow-sm"
            >
              <p className="text-3xl font-bold text-primary">{stat.value}</p>
              <p className="mt-1 text-sm text-muted-foreground">{stat.label}</p>
            </motion.div>
          ))}
        </motion.div>
      </main>

      <Footer />
      
      <RentalModal
        device={selectedDevice}
        open={isModalOpen}
        onOpenChange={setIsModalOpen}
      />
    </div>
  )
}
