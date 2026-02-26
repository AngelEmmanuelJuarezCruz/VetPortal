-- Get the first user with a tenant
-- Then insert test client, pets, and appointment

-- Suponiendo que existe un usuario con tenant_id = 1
-- Cliente
INSERT INTO clientes (uuid, veterinaria_id, nombre, apellido, telefono, correo, created_at, updated_at)
VALUES (
    (SELECT lower(hex(randomblob(16)))),
    1,
    'Juan',
    'Pérez',
    '833 181 8600',
    'ac5892496@gmail.com',
    datetime('now'),
    datetime('now')
);

-- Mascotas (3 pets)
INSERT INTO mascotas (cliente_id, veterinaria_id, nombre, especie, raza, color, fecha_nacimiento, descripcion, created_at, updated_at)
VALUES (
    (SELECT MAX(id) FROM clientes),
    1,
    'Max',
    'Perro',
    'Labrador',
    'Negro',
    date('now', '-3 years'),
    'Mascota de prueba para Juan',
    datetime('now'),
    datetime('now')
);

INSERT INTO mascotas (cliente_id, veterinaria_id, nombre, especie, raza, color, fecha_nacimiento, descripcion, created_at, updated_at)
VALUES (
    (SELECT MAX(id) FROM clientes),
    1,
    'Luna',
    'Gato',
    'Siames',
    'Blanco',
    date('now', '-2 years'),
    'Mascota de prueba para Juan',
    datetime('now'),
    datetime('now')
);

INSERT INTO mascotas (cliente_id, veterinaria_id, nombre, especie, raza, color, fecha_nacimiento, descripcion, created_at, updated_at)
VALUES (
    (SELECT MAX(id) FROM clientes),
    1,
    'Buddy',
    'Perro',
    'Golden Retriever',
    'Dorado',
    date('now', '-1 year'),
    'Mascota de prueba para Juan',
    datetime('now'),
    datetime('now')
);

-- Veterinarian (if not exists)
INSERT OR IGNORE INTO veterinarios (veterinaria_id, nombre, apellido, telefono, especializacion, created_at, updated_at)
VALUES (
    1,
    'Dr. García',
    'Veterinario',
    '555-1234',
    'General',
    datetime('now'),
    datetime('now')
);

-- Appointment for tomorrow at 10:00 AM
INSERT INTO citas (veterinaria_id, cliente_id, mascota_id, veterinario_id, user_id, fecha, hora, motivo, estado, created_at, updated_at)
VALUES (
    1,
    (SELECT MAX(id) FROM clientes),
    (SELECT MIN(id) FROM mascotas WHERE cliente_id = (SELECT MAX(id) FROM clientes)),
    (SELECT id FROM veterinarios WHERE veterinaria_id = 1 LIMIT 1),
    (SELECT id FROM users WHERE tenant_id = 1 LIMIT 1),
    date('now', '+1 day'),
    '10:00',
    'Chequeo de rutina y prueba de recordatorio de email',
    'pendiente',
    datetime('now'),
    datetime('now')
);
