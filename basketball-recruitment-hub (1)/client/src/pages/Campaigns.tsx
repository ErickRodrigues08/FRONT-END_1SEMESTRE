import { trpc } from "@/lib/trpc";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Loader2, Plus, Send } from "lucide-react";
import { useState } from "react";
import { Link } from "wouter";

export default function Campaigns() {
  const { data: campaigns, isLoading } = trpc.campaigns.list.useQuery();;
  const { data: stats } = trpc.campaigns.getStats.useQuery();

  const getStatusColor = (status: string) => {
    switch (status) {
      case "draft":
        return "bg-gray-100 text-gray-800";
      case "sent":
        return "bg-blue-100 text-blue-800";
      case "completed":
        return "bg-green-100 text-green-800";
      default:
        return "bg-gray-100 text-gray-800";
    }
  };

  const getStatusLabel = (status: string) => {
    switch (status) {
      case "draft":
        return "Rascunho";
      case "sent":
        return "Enviado";
      case "completed":
        return "Concluído";
      default:
        return status;
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container space-y-8">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div className="space-y-2">
            <h1 className="text-3xl font-bold text-foreground">Campanhas de Email</h1>
            <p className="text-muted-foreground">Gerencie suas campanhas de recrutamento</p>
          </div>
          <Link href="/campaigns/new">
            <Button className="gap-2">
              <Plus className="w-4 h-4" />
              Nova Campanha
            </Button>
          </Link>
        </div>

        {/* Stats */}
        {stats && (
          <div className="grid md:grid-cols-3 gap-4">
            {[
              { label: "E-mails Enviados", value: stats.sent },
              { label: "E-mails Abertos", value: stats.opened },
              { label: "Respostas", value: stats.replied },
            ].map((stat, i) => (
              <Card key={i} className="p-6 border-border/50">
                <p className="text-sm text-muted-foreground">{stat.label}</p>
                <p className="text-2xl font-bold text-foreground mt-2">{stat.value}</p>
              </Card>
            ))}
          </div>
        )}

        {/* Campaigns List */}
        {isLoading ? (
          <div className="flex justify-center py-12">
            <Loader2 className="w-8 h-8 animate-spin text-primary" />
          </div>
        ) : campaigns && campaigns.length > 0 ? (
          <div className="space-y-4">
            {campaigns.map((campaign) => (
              <Card key={campaign.id} className="p-6 border-border/50 hover:border-primary/30 transition-colors">
                <div className="flex items-center justify-between">
                  <div className="flex-1">
                    <div className="flex items-center gap-3 mb-2">
                      <h3 className="font-semibold text-foreground">Campanha #{campaign.id}</h3>
                      <span className={`text-xs px-2 py-1 rounded ${getStatusColor(campaign.status || "")}`}>
                        {getStatusLabel(campaign.status || "")}
                      </span>
                    </div>
                    <p className="text-sm text-muted-foreground">
                      Criada em {new Date(campaign.createdAt || new Date()).toLocaleDateString("pt-BR")}
                    </p>
                  </div>
                  <div className="flex gap-2">
                    {campaign.status === "draft" && (
                      <Button size="sm" className="gap-2">
                        <Send className="w-4 h-4" />
                        Enviar
                      </Button>
                    )}
                    <Button size="sm" variant="outline">
                      Ver Detalhes
                    </Button>
                  </div>
                </div>
              </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-12">
            <p className="text-muted-foreground mb-4">Nenhuma campanha criada ainda</p>
            <Link href="/campaigns/new">
              <Button>Criar Primeira Campanha</Button>
            </Link>
          </div>
        )}
      </div>
    </div>
  );
}
