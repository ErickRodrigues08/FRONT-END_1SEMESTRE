import { useAuth } from "@/_core/hooks/useAuth";
import { trpc } from "@/lib/trpc";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { toast } from "sonner";
import { useState } from "react";
import { Loader2 } from "lucide-react";

const POSITIONS = ["PG", "SG", "SF", "PF", "C"];

export default function Profile() {
  const { user } = useAuth();
  const [isEditing, setIsEditing] = useState(false);

  const { data: profile, isLoading } = trpc.athlete.getProfile.useQuery();
  const updateProfileMutation = trpc.athlete.updateProfile.useMutation({
    onSuccess: () => {
      toast.success("Perfil atualizado com sucesso!");
      setIsEditing(false);
    },
    onError: (error) => {
      toast.error(error.message);
    },
  });

  const [formData, setFormData] = useState({
    age: profile?.age?.toString() || "",
    height: profile?.height?.toString() || "",
    position: profile?.position || "",
    school: profile?.school || "",
    bio: profile?.bio || "",
  });

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateProfileMutation.mutate({
      age: formData.age ? parseInt(formData.age) : undefined,
      height: formData.height ? parseInt(formData.height) : undefined,
      position: formData.position as any,
      school: formData.school || undefined,
      bio: formData.bio || undefined,
    });
  };

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <Loader2 className="w-8 h-8 animate-spin text-primary" />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container max-w-2xl">
        <div className="space-y-8">
          {/* Header */}
          <div className="space-y-2">
            <h1 className="text-3xl font-bold text-foreground">Meu Perfil</h1>
            <p className="text-muted-foreground">Gerencie suas informações e estatísticas de atleta</p>
          </div>

          {/* Profile Card */}
          <Card className="p-8 border-border/50">
            <div className="space-y-6">
              {/* User Info */}
              <div className="space-y-2">
                <h2 className="text-xl font-semibold text-foreground">Informações Pessoais</h2>
                <div className="grid grid-cols-2 gap-4 mt-4">
                  <div>
                    <Label className="text-sm text-muted-foreground">Nome</Label>
                    <div className="mt-1 p-3 bg-muted/50 rounded-lg text-foreground">{user?.name}</div>
                  </div>
                  <div>
                    <Label className="text-sm text-muted-foreground">Email</Label>
                    <div className="mt-1 p-3 bg-muted/50 rounded-lg text-foreground">{user?.email}</div>
                  </div>
                </div>
              </div>

              {/* Divider */}
              <div className="border-t border-border/50" />

              {/* Athletic Profile */}
              <div className="space-y-4">
                <div className="flex justify-between items-center">
                  <h2 className="text-xl font-semibold text-foreground">Perfil Atlético</h2>
                  <Button
                    variant={isEditing ? "default" : "outline"}
                    onClick={() => setIsEditing(!isEditing)}
                  >
                    {isEditing ? "Cancelar" : "Editar"}
                  </Button>
                </div>

                {isEditing ? (
                  <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="grid md:grid-cols-2 gap-4">
                      <div className="space-y-2">
                        <Label htmlFor="age">Idade</Label>
                        <Input
                          id="age"
                          type="number"
                          min="16"
                          max="40"
                          value={formData.age}
                          onChange={(e) => setFormData({ ...formData, age: e.target.value })}
                          placeholder="Ex: 19"
                        />
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="height">Altura (cm)</Label>
                        <Input
                          id="height"
                          type="number"
                          min="150"
                          max="230"
                          value={formData.height}
                          onChange={(e) => setFormData({ ...formData, height: e.target.value })}
                          placeholder="Ex: 195"
                        />
                      </div>
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                      <div className="space-y-2">
                        <Label htmlFor="position">Posição</Label>
                        <Select value={formData.position} onValueChange={(value) => setFormData({ ...formData, position: value })}>
                          <SelectTrigger id="position">
                            <SelectValue placeholder="Selecione uma posição" />
                          </SelectTrigger>
                          <SelectContent>
                            {POSITIONS.map((pos) => (
                              <SelectItem key={pos} value={pos}>
                                {pos}
                              </SelectItem>
                            ))}
                          </SelectContent>
                        </Select>
                      </div>
                      <div className="space-y-2">
                        <Label htmlFor="school">Escola/Time Atual</Label>
                        <Input
                          id="school"
                          value={formData.school}
                          onChange={(e) => setFormData({ ...formData, school: e.target.value })}
                          placeholder="Ex: Colégio ABC"
                        />
                      </div>
                    </div>

                    <div className="space-y-2">
                      <Label htmlFor="bio">Bio</Label>
                      <Textarea
                        id="bio"
                        value={formData.bio}
                        onChange={(e) => setFormData({ ...formData, bio: e.target.value })}
                        placeholder="Descreva seus objetivos e conquistas..."
                        className="min-h-32"
                      />
                    </div>

                    <Button type="submit" className="w-full" disabled={updateProfileMutation.isPending}>
                      {updateProfileMutation.isPending ? (
                        <>
                          <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                          Salvando...
                        </>
                      ) : (
                        "Salvar Alterações"
                      )}
                    </Button>
                  </form>
                ) : (
                  <div className="grid md:grid-cols-2 gap-4">
                    <div className="p-4 bg-muted/50 rounded-lg">
                      <p className="text-sm text-muted-foreground">Idade</p>
                      <p className="text-lg font-semibold text-foreground">{profile?.age || "—"}</p>
                    </div>
                    <div className="p-4 bg-muted/50 rounded-lg">
                      <p className="text-sm text-muted-foreground">Altura</p>
                      <p className="text-lg font-semibold text-foreground">{profile?.height ? `${profile.height} cm` : "—"}</p>
                    </div>
                    <div className="p-4 bg-muted/50 rounded-lg">
                      <p className="text-sm text-muted-foreground">Posição</p>
                      <p className="text-lg font-semibold text-foreground">{profile?.position || "—"}</p>
                    </div>
                    <div className="p-4 bg-muted/50 rounded-lg">
                      <p className="text-sm text-muted-foreground">Escola/Time</p>
                      <p className="text-lg font-semibold text-foreground">{profile?.school || "—"}</p>
                    </div>
                  </div>
                )}
              </div>
            </div>
          </Card>

          {/* Statistics */}
          <Card className="p-8 border-border/50">
            <h2 className="text-xl font-semibold text-foreground mb-4">Estatísticas</h2>
            <div className="grid md:grid-cols-3 gap-4">
              {[
                { label: "PPG (Pontos por Jogo)", key: "PPG" },
                { label: "RPG (Rebotes por Jogo)", key: "RPG" },
                { label: "APG (Assistências por Jogo)", key: "APG" },
              ].map((stat) => (
                <div key={stat.key} className="p-4 bg-muted/50 rounded-lg">
                  <p className="text-sm text-muted-foreground">{stat.label}</p>
                  <p className="text-2xl font-bold text-primary">
                    {(profile?.statistics as any)?.[stat.key] || "—"}
                  </p>
                </div>
              ))}
            </div>
          </Card>
        </div>
      </div>
    </div>
  );
}
