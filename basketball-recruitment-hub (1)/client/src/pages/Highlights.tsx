import { trpc } from "@/lib/trpc";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Loader2, Trash2, Plus } from "lucide-react";
import { useState } from "react";
import { toast } from "sonner";

const CATEGORIES = ["season", "game", "training"];

export default function Highlights() {
  const { data: highlights, isLoading } = trpc.highlights.list.useQuery();
  const createMutation = trpc.highlights.create.useMutation({
    onSuccess: () => {
      toast.success("Highlight adicionado com sucesso!");
      setShowForm(false);
      setFormData({ title: "", description: "", videoUrl: "", category: "game" });
    },
    onError: (error) => {
      toast.error(error.message);
    },
  });

  const [showForm, setShowForm] = useState(false);
  const [formData, setFormData] = useState({
    title: "",
    description: "",
    videoUrl: "",
    category: "game",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    createMutation.mutate({
      title: formData.title,
      description: formData.description,
      videoUrl: formData.videoUrl,
      category: formData.category as any,
    });
  };

  const isYoutubeUrl = (url: string) => url.includes("youtube.com") || url.includes("youtu.be");

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container space-y-8">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div className="space-y-2">
            <h1 className="text-3xl font-bold text-foreground">Meus Highlights</h1>
            <p className="text-muted-foreground">Gerencie seus vídeos de basquete</p>
          </div>
          <Button onClick={() => setShowForm(!showForm)} className="gap-2">
            <Plus className="w-4 h-4" />
            Novo Highlight
          </Button>
        </div>

        {/* Add Form */}
        {showForm && (
          <Card className="p-8 border-border/50">
            <form onSubmit={handleSubmit} className="space-y-6">
              <div className="grid md:grid-cols-2 gap-4">
                <div className="space-y-2">
                  <Label htmlFor="title">Título</Label>
                  <Input
                    id="title"
                    value={formData.title}
                    onChange={(e) => setFormData({ ...formData, title: e.target.value })}
                    placeholder="Ex: Highlights - Jogo Final"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="category">Categoria</Label>
                  <Select value={formData.category} onValueChange={(value) => setFormData({ ...formData, category: value })}>
                    <SelectTrigger id="category">
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      {CATEGORIES.map((cat) => (
                        <SelectItem key={cat} value={cat}>
                          {cat === "season" ? "Temporada" : cat === "game" ? "Jogo" : "Treino"}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              </div>

              <div className="space-y-2">
                <Label htmlFor="videoUrl">URL do Vídeo (YouTube ou S3)</Label>
                <Input
                  id="videoUrl"
                  type="url"
                  value={formData.videoUrl}
                  onChange={(e) => setFormData({ ...formData, videoUrl: e.target.value })}
                  placeholder="https://youtube.com/watch?v=..."
                  required
                />
              </div>

              <div className="space-y-2">
                <Label htmlFor="description">Descrição</Label>
                <Textarea
                  id="description"
                  value={formData.description}
                  onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                  placeholder="Descreva o vídeo..."
                  className="min-h-24"
                />
              </div>

              <div className="flex gap-4">
                <Button type="submit" disabled={createMutation.isPending}>
                  {createMutation.isPending ? (
                    <>
                      <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                      Salvando...
                    </>
                  ) : (
                    "Adicionar Highlight"
                  )}
                </Button>
                <Button type="button" variant="outline" onClick={() => setShowForm(false)}>
                  Cancelar
                </Button>
              </div>
            </form>
          </Card>
        )}

        {/* Highlights List */}
        {isLoading ? (
          <div className="flex justify-center py-12">
            <Loader2 className="w-8 h-8 animate-spin text-primary" />
          </div>
        ) : highlights && highlights.length > 0 ? (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {highlights.map((highlight) => (
              <Card key={highlight.id} className="p-6 border-border/50 overflow-hidden">
                {/* Video Preview */}
                <div className="w-full h-40 bg-muted/50 rounded-lg mb-4 flex items-center justify-center">
                  {isYoutubeUrl(highlight.videoUrl) ? (
                    <div className="text-center">
                      <div className="w-12 h-12 bg-red-500/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span className="text-red-500 font-bold">▶</span>
                      </div>
                      <p className="text-xs text-muted-foreground">YouTube</p>
                    </div>
                  ) : (
                    <div className="text-center">
                      <div className="w-12 h-12 bg-primary/20 rounded-lg flex items-center justify-center mx-auto mb-2">
                        <span className="text-primary font-bold">▶</span>
                      </div>
                      <p className="text-xs text-muted-foreground">Vídeo</p>
                    </div>
                  )}
                </div>

                <h3 className="font-semibold text-foreground mb-2">{highlight.title}</h3>
                <p className="text-sm text-muted-foreground mb-3 line-clamp-2">{highlight.description}</p>

                <div className="flex items-center justify-between">
                  <span className="text-xs px-2 py-1 bg-primary/10 text-primary rounded">
                    {highlight.category === "season" ? "Temporada" : highlight.category === "game" ? "Jogo" : "Treino"}
                  </span>
                  <Button variant="ghost" size="sm" className="text-destructive hover:text-destructive">
                    <Trash2 className="w-4 h-4" />
                  </Button>
                </div>
              </Card>
            ))}
          </div>
        ) : (
          <div className="text-center py-12">
            <p className="text-muted-foreground mb-4">Nenhum highlight adicionado ainda</p>
            <Button onClick={() => setShowForm(true)}>Adicionar Primeiro Highlight</Button>
          </div>
        )}
      </div>
    </div>
  );
}
