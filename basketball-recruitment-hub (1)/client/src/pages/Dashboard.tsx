import { useAuth } from "@/_core/hooks/useAuth";
import { trpc } from "@/lib/trpc";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { BarChart3, Mail, Eye, MessageSquare, Loader2 } from "lucide-react";
import { Link } from "wouter";

export default function Dashboard() {
  const { user } = useAuth();
  const { data: stats, isLoading } = trpc.dashboard.getStats.useQuery();
  const { data: campaigns } = trpc.dashboard.getRecentCampaigns.useQuery();

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <Loader2 className="w-8 h-8 animate-spin text-primary" />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container space-y-8">
        {/* Header */}
        <div className="space-y-2">
          <h1 className="text-3xl font-bold text-foreground">Dashboard</h1>
          <p className="text-muted-foreground">Bem-vindo, {user?.name}! Acompanhe suas campanhas de recrutamento</p>
        </div>

        {/* Stats Grid */}
        <div className="grid md:grid-cols-4 gap-4">
          {[
            {
              icon: Mail,
              label: "E-mails Enviados",
              value: stats?.sent || 0,
              color: "text-blue-500",
            },
            {
              icon: Eye,
              label: "E-mails Abertos",
              value: stats?.opened || 0,
              color: "text-green-500",
            },
            {
              icon: MessageSquare,
              label: "Respostas",
              value: stats?.replied || 0,
              color: "text-purple-500",
            },
            {
              icon: BarChart3,
              label: "Taxa de Abertura",
              value: `${stats?.openRate || 0}%`,
              color: "text-orange-500",
            },
          ].map((stat, i) => (
            <Card key={i} className="p-6 border-border/50">
              <div className="flex items-start justify-between">
                <div>
                  <p className="text-sm text-muted-foreground">{stat.label}</p>
                  <p className="text-2xl font-bold text-foreground mt-2">{stat.value}</p>
                </div>
                <stat.icon className={`w-8 h-8 ${stat.color} opacity-20`} />
              </div>
            </Card>
          ))}
        </div>

        {/* Actions */}
        <div className="grid md:grid-cols-3 gap-4">
          <Link href="/highlights">
            <Button className="w-full h-12">Gerenciar Highlights</Button>
          </Link>
          <Link href="/colleges">
            <Button className="w-full h-12" variant="outline">Explorar Faculdades</Button>
          </Link>
          <Link href="/campaigns">
            <Button className="w-full h-12" variant="outline">Criar Campanha</Button>
          </Link>
        </div>

        {/* Recent Campaigns */}
        <Card className="p-8 border-border/50">
          <h2 className="text-xl font-semibold text-foreground mb-6">Campanhas Recentes</h2>
          {campaigns && campaigns.length > 0 ? (
            <div className="space-y-4">
              {campaigns.map((campaign) => (
                <div key={campaign.id} className="flex items-center justify-between p-4 bg-muted/50 rounded-lg">
                  <div>
                    <p className="font-semibold text-foreground">Campanha #{campaign.id}</p>
                    <p className="text-sm text-muted-foreground">
                      Status: <span className="capitalize">{campaign.status}</span>
                    </p>
                  </div>
                  <Button variant="outline" size="sm">Ver Detalhes</Button>
                </div>
              ))}
            </div>
          ) : (
            <div className="text-center py-12">
              <p className="text-muted-foreground mb-4">Nenhuma campanha criada ainda</p>
              <Link href="/campaigns">
                <Button>Criar Primeira Campanha</Button>
              </Link>
            </div>
          )}
        </Card>
      </div>
    </div>
  );
}
