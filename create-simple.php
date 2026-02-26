<?php
// Script simple para crear cliente, 3 mascotas y cita de prueba

$dbPath = __DIR__ . '/database.sqlite';
$db = new SQLite3($dbPath);

// Obtener primer usuario con tenant_id
$result = $db->querySingle("SELECT id, tenant_id FROM users WHERE tenant_id IS NOT NULL LIMIT 1", SQLITE3_ASSOC);

if (!$result) {
    echo "Error: No hay usuario con tenant\n";
    exit(1);
}

$userId = $result['id'];
$veterinariaId = $result['tenant_id'];

echo "✓ Usuario encontrado (ID: $userId, Veterinaria: $veterinariaId)\n";

// Crear cliente
$uuid = generateUUID();
$stmt = $db->prepare("INSERT INTO clientes (uuid, veterinaria_id, nombre, apellido, telefono, correo, created_at, updated_at) 
                      VALUES (:uuid, :vet_id, :nombre, :apellido, :telefono, :correo, datetime('now'), datetime('now'))");
$stmt->bindValue(':uuid', $uuid);
$stmt->bindValue(':vet_id', $veterinariaId);
$stmt->bindValue(':nombre', 'Juan');
$stmt->bindValue(':apellido', 'Pérez');
$stmt->bindValue(':telefono', '833 181 8600');
$stmt->bindValue(':correo', 'ac5892496@gmail.com');
$stmt->execute();

$clienteId = $db->lastInsertRowID();
echo "✓ Cliente creado (ID: $clienteId): Juan Pérez\n";
echo "  Email: ac5892496@gmail.com\n";
echo "  Teléfono: 833 181 8600\n";

// Crear 3 mascotas
$pets = [
    ['Max', 'Perro', 'Labrador', 'Negro'],
    ['Luna', 'Gato', 'Siames', 'Blanco'],
    ['Buddy', 'Perro', 'Golden Retriever', 'Dorado']
];

$petIds = [];
echo "\n✓ Mascotas creadas:\n";

foreach ($pets as $pet) {
    $stmt = $db->prepare("INSERT INTO mascotas (cliente_id, veterinaria_id, nombre, especie, raza, color, fecha_nacimiento, descripcion, created_at, updated_at)
                          VALUES (:cliente_id, :vet_id, :nombre, :especie, :raza, :color, date('now', '-' || :years || ' years'), :desc, datetime('now'), datetime('now'))");
    $stmt->bindValue(':cliente_id', $clienteId);
    $stmt->bindValue(':vet_id', $veterinariaId);
    $stmt->bindValue(':nombre', $pet[0]);
    $stmt->bindValue(':especie', $pet[1]);
    $stmt->bindValue(':raza', $pet[2]);
    $stmt->bindValue(':color', $pet[3]);
    $stmt->bindValue(':years', rand(1, 5));
    $stmt->bindValue(':desc', "Mascota de prueba para Juan");
    $stmt->execute();
    
    $petId = $db->lastInsertRowID();
    $petIds[] = $petId;
    echo "  - {$pet[0]} ({$pet[1]}) ID: $petId\n";
}

// Obtener o crear veterinario
$vet = $db->querySingle("SELECT id FROM veterinarios WHERE veterinaria_id = $veterinariaId LIMIT 1", SQLITE3_ASSOC);

if (!$vet) {
    $stmt = $db->prepare("INSERT INTO veterinarios (veterinaria_id, nombre, apellido, telefono, especializacion, created_at, updated_at)
                          VALUES (:vet_id, :nombre, :apellido, :telefono, :especializacion, datetime('now'), datetime('now'))");
    $stmt->bindValue(':vet_id', $veterinariaId);
    $stmt->bindValue(':nombre', 'Dr. García');
    $stmt->bindValue(':apellido', 'Sánchez');
    $stmt->bindValue(':telefono', '555-1234');
    $stmt->bindValue(':especializacion', 'General');
    $stmt->execute();
    $vetId = $db->lastInsertRowID();
    echo "\n✓ Veterinario creado: Dr. García\n";
} else {
    $vetId = $vet['id'];
    echo "\n✓ Veterinario existente: ID $vetId\n";
}

// Crear cita para mañana a las 10:00
$stmt = $db->prepare("INSERT INTO citas (veterinaria_id, cliente_id, mascota_id, veterinario_id, user_id, fecha, hora, motivo, estado, created_at, updated_at)
                      VALUES (:vet_id, :cliente_id, :mascota_id, :vet_profesional, :user_id, date('now', '+1 day'), '10:00', :motivo, 'pendiente', datetime('now'), datetime('now'))");
$stmt->bindValue(':vet_id', $veterinariaId);
$stmt->bindValue(':cliente_id', $clienteId);
$stmt->bindValue(':mascota_id', $petIds[0]);
$stmt->bindValue(':vet_profesional', $vetId);
$stmt->bindValue(':user_id', $userId);
$stmt->bindValue(':motivo', 'Chequeo de rutina y prueba de recordatorio de email');
$stmt->execute();

$citaId = $db->lastInsertRowID();

// Relacionar mascota con cita
$stmt = $db->prepare("INSERT INTO cita_mascota (cita_id, mascota_id) VALUES (:cita_id, :mascota_id)");
$stmt->bindValue(':cita_id', $citaId);
$stmt->bindValue(':mascota_id', $petIds[0]);
$stmt->execute();

echo "\n✓ Cita agendada:\n";
echo "  Cita ID: $citaId\n";
echo "  Mascota: Max\n";
echo "  Fecha: " . date('Y-m-d', strtotime('+1 day')) . "\n";
echo "  Hora: 10:00\n";
echo "  Estado: pendiente\n";

echo "\n" . str_repeat("=", 50) . "\n";
echo "DATOS DE PRUEBA CREADOS EXITOSAMENTE!\n";
echo str_repeat("=", 50) . "\n";

$db->close();

function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}
?>
