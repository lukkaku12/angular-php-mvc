export interface Project {
    id?: number;
    titulo: string;
    descripcion: string;
    fecha_inicio: Date | string;      // tipo string porque viene en formato 'YYYY-MM-DD'
    fecha_entrega: Date | string;     // igual que el anterior
    estado: string;
    user_id?: number;
    created_at: Date | string; 
}