<!-- resources/views/mapa/index.blade.php -->
@extends('layouts.app')

@section('title', 'Mapa do Loteamento')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Mapa do Loteamento</h1>
            </div>
            <div class="col-sm-6">
                <div class="float-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default" id="btn-zoom-in">
                            <i class="fas fa-search-plus"></i>
                        </button>
                        <button type="button" class="btn btn-default" id="btn-zoom-out">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-default" id="btn-reset">
                            <i class="fas fa-sync-alt"></i> Redefinir
                        </button>
                    </div>
                    <a href="{{ route('lotes.index') }}" class="btn btn-secondary ml-2">
                        <i class="fas fa-list"></i> Lista de Lotes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Legenda -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center">
                            <strong class="mr-3">Legenda:</strong>
                            <div class="legend-item mr-3">
                                <span class="legend-color" style="background-color: #28a745;"></span>
                                <span class="legend-text">Disponível</span>
                            </div>
                            <div class="legend-item mr-3">
                                <span class="legend-color" style="background-color: #ffc107;"></span>
                                <span class="legend-text">Reservado</span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: #dc3545;"></span>
                                <span class="legend-text">Vendido</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapa -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Visualização do Loteamento</h3>
                        <div class="card-tools">
                            <span class="badge badge-success">{{ $lotes->where('status', 'disponivel')->count() }} Disponíveis</span>
                            <span class="badge badge-warning ml-1">{{ $lotes->where('status', 'reservado')->count() }} Reservados</span>
                            <span class="badge badge-danger ml-1">{{ $lotes->where('status', 'vendido')->count() }} Vendidos</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="mapa-container" style="position: relative; overflow: hidden; background: #f8f9fa; min-height: 600px; border: 1px solid #dee2e6;">
                            <div id="loteamento-map" style="position: relative; width: 100%; height: 100%; transform-origin: 0 0;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Detalhes -->
        <div class="modal fade" id="loteModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detalhes do Lote</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="lote-details">
                        <!-- Conteúdo carregado via JavaScript -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <a href="#" class="btn btn-primary" id="btn-editar-lote">Editar Lote</a>
                        <a href="#" class="btn btn-info" id="btn-visualizar-lote">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.legend-item {
    display: flex;
    align-items: center;
    margin-right: 15px;
}

.legend-color {
    width: 20px;
    height: 20px;
    border-radius: 3px;
    margin-right: 5px;
    border: 1px solid #ddd;
}

.legend-text {
    font-size: 14px;
}

.lote-item {
    position: absolute;
    border: 2px solid #333;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #000;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.lote-item:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    z-index: 1000;
}

.lote-numero {
    background: rgba(255,255,255,0.9);
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

#mapa-container {
    cursor: grab;
}

#mapa-container:active {
    cursor: grabbing;
}
</style>
@endpush

@push('scripts')
<script>
class MapaLoteamento {
    constructor() {
        this.lotes = [];
        this.scale = 1;
        this.translateX = 0;
        this.translateY = 0;
        this.isDragging = false;
        this.lastX = 0;
        this.lastY = 0;
        
        this.init();
    }
    
    init() {
        this.loadLotes();
        this.setupEventListeners();
        this.setupDrag();
    }
    
    async loadLotes() {
        try {
            const response = await fetch('{{ route("mapa.lotes") }}');
            this.lotes = await response.json();
            this.renderLotes();
        } catch (error) {
            console.error('Erro ao carregar lotes:', error);
        }
    }
    
    renderLotes() {
        const container = document.getElementById('loteamento-map');
        container.innerHTML = '';
        
        this.lotes.forEach(lote => {
            if (lote.pos_x !== null && lote.pos_y !== null) {
                this.createLoteElement(lote);
            }
        });
    }
    
    createLoteElement(lote) {
        const container = document.getElementById('loteamento-map');
        const loteDiv = document.createElement('div');
        
        loteDiv.className = 'lote-item';
        loteDiv.style.left = (lote.pos_x * this.scale + this.translateX) + 'px';
        loteDiv.style.top = (lote.pos_y * this.scale + this.translateY) + 'px';
        loteDiv.style.width = (lote.largura * this.scale) + 'px';
        loteDiv.style.height = (lote.altura * this.scale) + 'px';
        loteDiv.style.backgroundColor = lote.cor;
        loteDiv.style.borderColor = this.darkenColor(lote.cor, 20);
        
        loteDiv.innerHTML = `
            <div class="lote-numero">${lote.numero}</div>
        `;
        
        loteDiv.title = lote.tooltip;
        
        loteDiv.addEventListener('click', (e) => {
            e.stopPropagation();
            this.showLoteDetails(lote);
        });
        
        container.appendChild(loteDiv);
    }
    
    darkenColor(color, percent) {
        const num = parseInt(color.replace("#", ""), 16);
        const amt = Math.round(2.55 * percent);
        const R = (num >> 16) - amt;
        const G = (num >> 8 & 0x00FF) - amt;
        const B = (num & 0x0000FF) - amt;
        return "#" + (
            0x1000000 +
            (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
            (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
            (B < 255 ? B < 1 ? 0 : B : 255)
        ).toString(16).slice(1);
    }
    
    showLoteDetails(lote) {
        const modal = document.getElementById('loteModal');
        const details = document.getElementById('lote-details');
        const btnEditar = document.getElementById('btn-editar-lote');
        const btnVisualizar = document.getElementById('btn-visualizar-lote');
        
        details.innerHTML = `
            <div class="row">
                <div class="col-6">
                    <strong>Número:</strong> ${lote.numero}
                </div>
                <div class="col-6">
                    <strong>Quadra:</strong> ${lote.quadra}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-6">
                    <strong>Área:</strong> ${lote.area} m²
                </div>
                <div class="col-6">
                    <strong>Valor:</strong> R$ ${lote.valor.toFixed(2).replace('.', ',')}
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <strong>Status:</strong> 
                    <span class="badge badge-${lote.status === 'disponivel' ? 'success' : lote.status === 'reservado' ? 'warning' : 'danger'}">
                        ${lote.status_formatado}
                    </span>
                </div>
            </div>
        `;
        
        btnEditar.href = lote.url_editar;
        btnVisualizar.href = lote.url_visualizar;
        
        $(modal).modal('show');
    }
    
    setupEventListeners() {
        // Zoom In
        document.getElementById('btn-zoom-in').addEventListener('click', () => {
            this.scale *= 1.2;
            this.applyTransform();
        });
        
        // Zoom Out
        document.getElementById('btn-zoom-out').addEventListener('click', () => {
            this.scale /= 1.2;
            this.applyTransform();
        });
        
        // Reset
        document.getElementById('btn-reset').addEventListener('click', () => {
            this.scale = 1;
            this.translateX = 0;
            this.translateY = 0;
            this.applyTransform();
        });
    }
    
    setupDrag() {
        const container = document.getElementById('mapa-container');
        
        container.addEventListener('mousedown', (e) => {
            this.isDragging = true;
            this.lastX = e.clientX;
            this.lastY = e.clientY;
            e.preventDefault();
        });
        
        document.addEventListener('mousemove', (e) => {
            if (!this.isDragging) return;
            
            const deltaX = e.clientX - this.lastX;
            const deltaY = e.clientY - this.lastY;
            
            this.translateX += deltaX;
            this.translateY += deltaY;
            
            this.lastX = e.clientX;
            this.lastY = e.clientY;
            
            this.applyTransform();
        });
        
        document.addEventListener('mouseup', () => {
            this.isDragging = false;
        });
        
        // Suporte a touch para mobile
        container.addEventListener('touchstart', (e) => {
            this.isDragging = true;
            this.lastX = e.touches[0].clientX;
            this.lastY = e.touches[0].clientY;
            e.preventDefault();
        });
        
        document.addEventListener('touchmove', (e) => {
            if (!this.isDragging) return;
            
            const deltaX = e.touches[0].clientX - this.lastX;
            const deltaY = e.touches[0].clientY - this.lastY;
            
            this.translateX += deltaX;
            this.translateY += deltaY;
            
            this.lastX = e.touches[0].clientX;
            this.lastY = e.touches[0].clientY;
            
            this.applyTransform();
        });
        
        document.addEventListener('touchend', () => {
            this.isDragging = false;
        });
    }
    
    applyTransform() {
        const map = document.getElementById('loteamento-map');
        map.style.transform = `translate(${this.translateX}px, ${this.translateY}px) scale(${this.scale})`;
    }
}

// Inicializar o mapa quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    new MapaLoteamento();
});
</script>
@endpush