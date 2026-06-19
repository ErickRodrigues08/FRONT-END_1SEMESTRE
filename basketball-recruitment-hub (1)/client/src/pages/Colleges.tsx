import { trpc } from "@/lib/trpc";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Loader2, MapPin, Star } from "lucide-react";
import { useState } from "react";
import { Link } from "wouter";

const DIVISIONS = ["NCAA D1", "NCAA D2", "NCAA D3", "NAIA", "JUCO"];
const STATES = ["CA", "TX", "FL", "NY", "PA", "OH", "IL", "MI", "NC", "GA"];

export default function Colleges() {
  const [division, setDivision] = useState<string>("");
  const [state, setState] = useState<string>("");
  const [search, setSearch] = useState<string>("");

  const { data: colleges, isLoading } = trpc.colleges.search.useQuery({
    division: division || undefined,
    state: state || undefined,
    search: search || undefined,
  });

  const { data: recommendations } = trpc.recommendations.getForAthlete.useQuery();

  const recommendedCollegeIds = new Set(recommendations?.map(r => r.collegeId) || []);

  return (
    <div className="min-h-screen bg-gradient-to-br from-background via-background to-muted/30 py-12">
      <div className="container space-y-8">
        {/* Header */}
        <div className="space-y-2">
          <h1 className="text-3xl font-bold text-foreground">Faculdades e Universidades</h1>
          <p className="text-muted-foreground">Explore oportunidades de recrutamento nos EUA</p>
        </div>

        {/* Filters */}
        <Card className="p-6 border-border/50">
          <div className="grid md:grid-cols-4 gap-4">
            <div className="space-y-2">
              <label className="text-sm font-medium text-foreground">Buscar</label>
              <Input
                placeholder="Nome da faculdade..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
              />
            </div>
            <div className="space-y-2">
              <label className="text-sm font-medium text-foreground">Divisão</label>
              <Select value={division} onValueChange={setDivision}>
                <SelectTrigger>
                  <SelectValue placeholder="Todas as divisões" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="">Todas as divisões</SelectItem>
                  {DIVISIONS.map((div) => (
                    <SelectItem key={div} value={div}>
                      {div}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="space-y-2">
              <label className="text-sm font-medium text-foreground">Estado</label>
              <Select value={state} onValueChange={setState}>
                <SelectTrigger>
                  <SelectValue placeholder="Todos os estados" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="">Todos os estados</SelectItem>
                  {STATES.map((st) => (
                    <SelectItem key={st} value={st}>
                      {st}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            </div>
            <div className="flex items-end">
              <Button className="w-full">Filtrar</Button>
            </div>
          </div>
        </Card>

        {/* Colleges Grid */}
        {isLoading ? (
          <div className="flex justify-center py-12">
            <Loader2 className="w-8 h-8 animate-spin text-primary" />
          </div>
        ) : colleges && colleges.length > 0 ? (
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {colleges.map((college) => {
              const isRecommended = recommendedCollegeIds.has(college.id);
              return (
                <Card key={college.id} className="p-6 border-border/50 hover:border-primary/30 transition-colors">
                  {isRecommended && (
                    <div className="flex items-center gap-1 text-sm text-orange-500 mb-3">
                      <Star className="w-4 h-4 fill-orange-500" />
                      Recomendado para você
                    </div>
                  )}
                  <h3 className="font-semibold text-foreground text-lg mb-2">{college.name}</h3>
                  <div className="space-y-2 mb-4">
                    <div className="flex items-center gap-2 text-sm text-muted-foreground">
                      <MapPin className="w-4 h-4" />
                      {college.city}, {college.state}
                    </div>
                    <div className="text-sm">
                      <span className="inline-block px-2 py-1 bg-primary/10 text-primary rounded text-xs font-medium">
                        {college.division}
                      </span>
                    </div>
                  </div>
                  <a href={`/colleges/${college.id}`}>
                    <Button className="w-full" variant="outline" size="sm">
                      Ver Detalhes
                    </Button>
                  </a>
                </Card>
              );
            })}
          </div>
        ) : (
          <div className="text-center py-12">
            <p className="text-muted-foreground">Nenhuma faculdade encontrada</p>
          </div>
        )}
      </div>
    </div>
  );
}
