import { useParams } from "wouter";
import { trpc } from "@/lib/trpc";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog";
import { Loader2, Mail, MapPin, Globe, Star, Send } from "lucide-react";
import { useState } from "react";
import { toast } from "sonner";

export default function CollegeDetail() {
  const { id } = useParams();
  const collegeId = id ? parseInt(id) : 0;

  const { data: college, isLoading: collegeLoading } = trpc.colleges.getById.useQuery(collegeId);
  const { data: coaches, isLoading: coachesLoading } = trpc.colleges.getCoaches.useQuery(collegeId);
  const [showSendModal, setShowSendModal] = useState(false);
  const [selectedHighlights, setSelectedHighlights] = useState<number[]>([]);
  const [message, setMessage] = useState("");

  const { data: highlights } = trpc.highlights.list.useQuery();

  const sendHighlightsMutation = trpc.campaigns.create.useMutation({
    onSuccess: () => {
      toast.success("Highlights enviados com sucesso!");
      setShowSendModal(false);
      setSelectedHighlights([]);
      setMessage("");
    },
    onError: (error: any) => {
      toast.error(error?.message || "Erro ao enviar highlights");
    },
  });

  const handleSendHighlights = () => {
    if (selectedHighlights.length === 0) {
      toast.error("Selecione pelo menos um highlight");
      return;
    }

    sendHighlightsMutation.mutate({
      templateId: 1,
      coachIds: coaches?.map(c => c.id) || [],
      personalMessage: message || undefined,
    });
  };

  if (collegeLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <Loader2 className="w-8 h-8 animate-spin text-primary" />
      </div>
    );
  }

  if (!college) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="text-center">
          <p className="text-muted-foreground mb-4">Faculdade não encontrada</p>
          <a href="/colleges">
            <Button>Voltar para Faculdades</Button>
          </a>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container space-y-8">
        {/* Header */}
        <div className="space-y-4">
          <a href="/colleges" className="text-primary hover:underline text-sm">
            ← Voltar para Faculdades
          </a>
          <div className="flex items-start justify-between">
            <div className="space-y-2">
              <h1 className="text-4xl font-bold text-foreground">{college.name}</h1>
              <div className="flex flex-wrap gap-3">
                <div className="flex items-center gap-2 text-muted-foreground">
                  <MapPin className="w-4 h-4" />
                  {college.city}, {college.state}
                </div>
                {college.website && (
                  <a href={college.website} target="_blank" rel="noopener noreferrer" className="flex items-center gap-2 text-primary hover:underline">
                    <Globe className="w-4 h-4" />
                    Website
                  </a>
                )}
              </div>
            </div>
            <Dialog open={showSendModal} onOpenChange={setShowSendModal}>
              <DialogTrigger asChild>
                <Button size="lg" className="gap-2">
                  <Send className="w-4 h-4" />
                  Enviar Highlights
                </Button>
              </DialogTrigger>
              <DialogContent className="max-w-2xl">
                <DialogHeader>
                  <DialogTitle>Enviar Highlights para {college.name}</DialogTitle>
                  <DialogDescription>
                    Selecione seus highlights e envie uma mensagem personalizada
                  </DialogDescription>
                </DialogHeader>

                <div className="space-y-6">
                  {/* Highlights Selection */}
                  <div className="space-y-3">
                    <Label className="text-base font-semibold">Selecione Highlights</Label>
                    <div className="space-y-2 max-h-48 overflow-y-auto">
                      {highlights && highlights.length > 0 ? (
                        highlights.map((highlight) => (
                          <div key={highlight.id} className="flex items-center gap-3 p-3 border border-border/50 rounded-lg hover:bg-muted/50 transition-colors">
                            <input
                              type="checkbox"
                              id={`highlight-${highlight.id}`}
                              checked={selectedHighlights.includes(highlight.id)}
                              onChange={(e) => {
                                if (e.target.checked) {
                                  setSelectedHighlights([...selectedHighlights, highlight.id]);
                                } else {
                                  setSelectedHighlights(selectedHighlights.filter(id => id !== highlight.id));
                                }
                              }}
                              className="w-4 h-4 cursor-pointer"
                            />
                            <label htmlFor={`highlight-${highlight.id}`} className="flex-1 cursor-pointer">
                              <p className="font-medium text-foreground">{highlight.title}</p>
                              <p className="text-sm text-muted-foreground">{highlight.description}</p>
                            </label>
                          </div>
                        ))
                      ) : (
                        <p className="text-muted-foreground text-sm">Nenhum highlight disponível. Adicione highlights primeiro.</p>
                      )}
                    </div>
                  </div>

                  {/* Message */}
                  <div className="space-y-2">
                    <Label htmlFor="message">Mensagem Personalizada (Opcional)</Label>
                    <Textarea
                      id="message"
                      value={message}
                      onChange={(e) => setMessage(e.target.value)}
                      placeholder="Escreva uma mensagem personalizada para o treinador..."
                      className="min-h-24"
                    />
                  </div>

                  {/* Send Button */}
                  <div className="flex gap-3">
                    <Button
                      onClick={handleSendHighlights}
                      disabled={sendHighlightsMutation.isPending || selectedHighlights.length === 0}
                      className="flex-1"
                    >
                      {sendHighlightsMutation.isPending ? (
                        <>
                          <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                          Enviando...
                        </>
                      ) : (
                        <>
                          <Send className="w-4 h-4 mr-2" />
                          Enviar Highlights
                        </>
                      )}
                    </Button>
                    <Button
                      variant="outline"
                      onClick={() => setShowSendModal(false)}
                    >
                      Cancelar
                    </Button>
                  </div>
                </div>
              </DialogContent>
            </Dialog>
          </div>
        </div>

        {/* College Info */}
        <div className="grid md:grid-cols-3 gap-6">
          <Card className="p-6 border-border/50">
            <p className="text-sm text-muted-foreground mb-2">Divisão</p>
            <p className="text-2xl font-bold text-primary">{college.division}</p>
          </Card>
          <Card className="p-6 border-border/50">
            <p className="text-sm text-muted-foreground mb-2">Estado</p>
            <p className="text-2xl font-bold text-foreground">{college.state}</p>
          </Card>
          <Card className="p-6 border-border/50">
            <p className="text-sm text-muted-foreground mb-2">Compatibilidade</p>
            <p className="text-2xl font-bold text-orange-500">
              {college.name ? "—" : "—"}%
            </p>
          </Card>
        </div>

        {/* Description */}
        {college && (
          <Card className="p-8 border-border/50">
            <h2 className="text-xl font-semibold text-foreground mb-4">Sobre a Universidade</h2>
            <p className="text-muted-foreground leading-relaxed">
              {college.name} é uma instituição de educação superior localizada em {college.city}, {college.state}.
              Oferece programas esportivos na divisão {college.division}.
            </p>
          </Card>
        )}

        {/* Coaches */}
        <Card className="p-8 border-border/50">
          <h2 className="text-xl font-semibold text-foreground mb-6">Treinadores</h2>
          {coachesLoading ? (
            <div className="flex justify-center py-8">
              <Loader2 className="w-6 h-6 animate-spin text-primary" />
            </div>
          ) : coaches && coaches.length > 0 ? (
            <div className="space-y-4">
              {coaches.map((coach) => (
                <div key={coach.id} className="p-4 border border-border/50 rounded-lg hover:border-primary/30 transition-colors">
                  <div className="flex items-start justify-between mb-2">
                    <div>
                      <h3 className="font-semibold text-foreground">{coach.firstName} {coach.lastName}</h3>
                      <p className="text-sm text-primary font-medium">{coach.position || "Assistente"}</p>
                    </div>
                    <div className="flex items-center gap-1 text-orange-500 text-xs font-semibold">
                      <Star className="w-3 h-3 fill-orange-500" />
                      Treinador
                    </div>
                  </div>
                  {coach.email && (
                    <a href={`mailto:${coach.email}`} className="flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors">
                      <Mail className="w-4 h-4" />
                      {coach.email}
                    </a>
                  )}
                  {coach.phone && (
                    <p className="text-sm text-muted-foreground mt-1">
                      Telefone: {coach.phone}
                    </p>
                  )}
                </div>
              ))}
            </div>
          ) : (
            <p className="text-muted-foreground">Nenhum treinador cadastrado</p>
          )}
        </Card>

        {/* Quick Send Section */}
        <Card className="p-8 border-border/50 bg-gradient-to-r from-primary/5 to-primary/10">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-lg font-semibold text-foreground mb-1">Pronto para enviar seus highlights?</h3>
              <p className="text-muted-foreground">Clique no botão acima para enviar seus melhores momentos diretamente para os treinadores</p>
            </div>
            <Dialog open={showSendModal} onOpenChange={setShowSendModal}>
              <DialogTrigger asChild>
                <Button className="gap-2">
                  <Send className="w-4 h-4" />
                  Enviar Agora
                </Button>
              </DialogTrigger>
              <DialogContent className="max-w-2xl">
                <DialogHeader>
                  <DialogTitle>Enviar Highlights para {college.name}</DialogTitle>
                  <DialogDescription>
                    Selecione seus highlights e envie uma mensagem personalizada
                  </DialogDescription>
                </DialogHeader>

                <div className="space-y-6">
                  <div className="space-y-3">
                    <Label className="text-base font-semibold">Selecione Highlights</Label>
                    <div className="space-y-2 max-h-48 overflow-y-auto">
                      {highlights && highlights.length > 0 ? (
                        highlights.map((highlight) => (
                          <div key={highlight.id} className="flex items-center gap-3 p-3 border border-border/50 rounded-lg hover:bg-muted/50 transition-colors">
                            <input
                              type="checkbox"
                              id={`highlight-${highlight.id}`}
                              checked={selectedHighlights.includes(highlight.id)}
                              onChange={(e) => {
                                if (e.target.checked) {
                                  setSelectedHighlights([...selectedHighlights, highlight.id]);
                                } else {
                                  setSelectedHighlights(selectedHighlights.filter(id => id !== highlight.id));
                                }
                              }}
                              className="w-4 h-4 cursor-pointer"
                            />
                            <label htmlFor={`highlight-${highlight.id}`} className="flex-1 cursor-pointer">
                              <p className="font-medium text-foreground">{highlight.title}</p>
                              <p className="text-sm text-muted-foreground">{highlight.description}</p>
                            </label>
                          </div>
                        ))
                      ) : (
                        <p className="text-muted-foreground text-sm">Nenhum highlight disponível. Adicione highlights primeiro.</p>
                      )}
                    </div>
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor="message">Mensagem Personalizada (Opcional)</Label>
                    <Textarea
                      id="message"
                      value={message}
                      onChange={(e) => setMessage(e.target.value)}
                      placeholder="Escreva uma mensagem personalizada para o treinador..."
                      className="min-h-24"
                    />
                  </div>

                  <div className="flex gap-3">
                    <Button
                      onClick={handleSendHighlights}
                      disabled={sendHighlightsMutation.isPending || selectedHighlights.length === 0}
                      className="flex-1"
                    >
                      {sendHighlightsMutation.isPending ? (
                        <>
                          <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                          Enviando...
                        </>
                      ) : (
                        <>
                          <Send className="w-4 h-4 mr-2" />
                          Enviar Highlights
                        </>
                      )}
                    </Button>
                    <Button
                      variant="outline"
                      onClick={() => setShowSendModal(false)}
                    >
                      Cancelar
                    </Button>
                  </div>
                </div>
              </DialogContent>
            </Dialog>
          </div>
        </Card>
      </div>
    </div>
  );
}
