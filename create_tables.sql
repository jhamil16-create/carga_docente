-- Crear tabla Rol
CREATE TABLE Rol (
  rol_id SERIAL PRIMARY KEY,
  nombre_rol VARCHAR(50) UNIQUE NOT NULL
);

-- Crear tabla Usuario
CREATE TABLE Usuario (
  usuario_id SERIAL PRIMARY KEY,
  rol_id INT NOT NULL,
  codigo_usuario VARCHAR(6) UNIQUE NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  email_institucional VARCHAR(150) UNIQUE NOT NULL,
  contraseña_hash VARCHAR(255) NOT NULL,
  activo BOOLEAN DEFAULT true,
  FOREIGN KEY (rol_id) REFERENCES Rol(rol_id)
);

-- Crear tabla Docente
CREATE TABLE Docente (
  docente_id SERIAL PRIMARY KEY,
  usuario_id INT UNIQUE NOT NULL,
  especialidad VARCHAR(100),
  telefono VARCHAR(20),
  fecha_registro DATE DEFAULT CURRENT_DATE,
  FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);

-- Crear tabla Materia
CREATE TABLE Materia (
  materia_id SERIAL PRIMARY KEY,
  nombre_materia VARCHAR(150) NOT NULL,
  codigo_materia VARCHAR(20) UNIQUE NOT NULL,
  creditos INT NOT NULL
);

-- Crear tabla Grupo
CREATE TABLE Grupo (
  grupo_id SERIAL PRIMARY KEY,
  materia_id INT NOT NULL,
  nombre_grupo VARCHAR(50) NOT NULL,
  capacidad_maxima INT NOT NULL,
  FOREIGN KEY (materia_id) REFERENCES Materia(materia_id)
);

-- Crear tabla Aula
CREATE TABLE Aula (
  aula_id SERIAL PRIMARY KEY,
  nombre_aula VARCHAR(50) UNIQUE NOT NULL,
  capacidad INT NOT NULL,
  ubicacion VARCHAR(100)
);

-- Crear tabla Horario
CREATE TABLE Horario (
  horario_id SERIAL PRIMARY KEY,
  dia_semana VARCHAR(10) NOT NULL,
  hora_inicio TIME NOT NULL,
  hora_fin TIME NOT NULL
);

-- Crear tabla Asignacion
CREATE TABLE Asignacion (
  asignacion_id SERIAL PRIMARY KEY,
  docente_id INT NOT NULL,
  grupo_id INT NOT NULL,
  aula_id INT NOT NULL,
  horario_id INT NOT NULL,
  fecha_asignacion DATE DEFAULT CURRENT_DATE,
  FOREIGN KEY (docente_id) REFERENCES Docente(docente_id),
  FOREIGN KEY (grupo_id) REFERENCES Grupo(grupo_id),
  FOREIGN KEY (aula_id) REFERENCES Aula(aula_id),
  FOREIGN KEY (horario_id) REFERENCES Horario(horario_id),
  UNIQUE (aula_id, horario_id),
  UNIQUE (docente_id, horario_id)
);

-- Crear tabla Asistencia
CREATE TABLE Asistencia (
  asistencia_id SERIAL PRIMARY KEY,
  docente_id INT NOT NULL,
  asignacion_id INT NOT NULL,
  fecha DATE NOT NULL,
  hora_entrada TIME,
  estado VARCHAR(20) NOT NULL,
  metodo_registro VARCHAR(50),
  FOREIGN KEY (docente_id) REFERENCES Docente(docente_id),
  FOREIGN KEY (asignacion_id) REFERENCES Asignacion(asignacion_id)
);

-- Crear tabla Reporte
CREATE TABLE Reporte (
  reporte_id SERIAL PRIMARY KEY,
  tipo_reporte VARCHAR(10) NOT NULL,
  fecha_generacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  datos JSONB
);

-- Crear tabla CargaMasiva
CREATE TABLE CargaMasiva (
  carga_id SERIAL PRIMARY KEY,
  archivo_nombre VARCHAR(255) NOT NULL,
  fecha_carga TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  registros_exitosos INT DEFAULT 0,
  registros_fallidos INT DEFAULT 0,
  usuario_admin_id INT NOT NULL,
  FOREIGN KEY (usuario_admin_id) REFERENCES Usuario(usuario_id)
);

-- Crear tabla Bitacora
CREATE TABLE Bitacora (
  bitacora_id SERIAL PRIMARY KEY,
  usuario_id INT NOT NULL,
  descripcion TEXT NOT NULL,
  fecha_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  ip_origen VARCHAR(45),
  FOREIGN KEY (usuario_id) REFERENCES Usuario(usuario_id)
);

-- Crear tabla ErroresCarga
CREATE TABLE ErroresCarga (
  error_id SERIAL PRIMARY KEY,
  carga_id INT NOT NULL,
  numero_fila INT NOT NULL,
  datos_originales TEXT,
  mensaje_error TEXT,
  fecha_error TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (carga_id) REFERENCES CargaMasiva(carga_id)
);