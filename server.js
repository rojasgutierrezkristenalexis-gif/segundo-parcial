import express from 'express';
import cors from 'cors';
import authRoutes from './routes/auth.routes.js'; 
import userRoutes from './routes/user.routes.js';

const app = express();

// Configuración de Middlewares
app.use(cors()); // Permite la comunicación con el Frontend
app.use(express.json()); // Permite recibir datos en formato JSON

// Rutas
app.use('/api/auth', authRoutes);
app.use('/api/users', userRoutes);

// Puerto y encendido del servidor
const PORT = 5000;
app.listen(PORT, () => {
    console.log(`Servidor activo en http://localhost:${PORT}`);
});