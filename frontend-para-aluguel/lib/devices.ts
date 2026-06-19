export interface Device {
  id: number
  modelo: string
  marca: string
  valor_diaria: number
  status: 'disponivel' | 'alugado'
  observacoes: string
  imagem: string
  categoria: string
}

export interface RentalRequest {
  cliente: string
  telefone: string
  dispositivo_id: number
  data_retirada: string
  data_prevista: string
}

// Simulated device data (in production, this would come from an API)
export const devices: Device[] = [
  {
    id: 1,
    modelo: 'Galaxy Tab S9',
    marca: 'Samsung',
    valor_diaria: 95.00,
    status: 'disponivel',
    observacoes: 'Tablet premium para reuniões e apresentações. Tela AMOLED de 11 polegadas.',
    imagem: '/devices/tablet-samsung.jpg',
    categoria: 'Tablet'
  },
  {
    id: 2,
    modelo: 'ThinkPad E14',
    marca: 'Lenovo',
    valor_diaria: 130.00,
    status: 'disponivel',
    observacoes: 'Notebook corporativo com SSD de 256GB e 8GB de RAM.',
    imagem: '/devices/notebook-lenovo.jpg',
    categoria: 'Notebook'
  },
  {
    id: 3,
    modelo: 'iPad Pro 12.9"',
    marca: 'Apple',
    valor_diaria: 150.00,
    status: 'disponivel',
    observacoes: 'Tablet profissional com chip M2 e suporte a Apple Pencil.',
    imagem: '/devices/ipad-pro.jpg',
    categoria: 'Tablet'
  },
  {
    id: 4,
    modelo: 'MacBook Air M3',
    marca: 'Apple',
    valor_diaria: 200.00,
    status: 'alugado',
    observacoes: 'Ultrabook leve e potente com chip M3 e 15 horas de bateria.',
    imagem: '/devices/macbook-air.jpg',
    categoria: 'Notebook'
  },
  {
    id: 5,
    modelo: 'Surface Pro 9',
    marca: 'Microsoft',
    valor_diaria: 180.00,
    status: 'disponivel',
    observacoes: '2-em-1 versátil com tela touchscreen de 13 polegadas.',
    imagem: '/devices/surface-pro.jpg',
    categoria: 'Tablet'
  },
  {
    id: 6,
    modelo: 'Galaxy Book3 Pro',
    marca: 'Samsung',
    valor_diaria: 160.00,
    status: 'disponivel',
    observacoes: 'Notebook premium com tela AMOLED e processador Intel Core i7.',
    imagem: '/devices/galaxy-book.jpg',
    categoria: 'Notebook'
  }
]

export function formatCurrency(value: number): string {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value)
}

export function calculateDays(startDate: string, endDate: string): number {
  const start = new Date(startDate)
  const end = new Date(endDate)
  const diffTime = Math.abs(end.getTime() - start.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
  return Math.max(diffDays, 1)
}
