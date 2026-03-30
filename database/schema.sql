CREATE TABLE IF NOT EXISTS vados (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    enable_jpg BOOLEAN DEFAULT 1,
    enable_webp BOOLEAN DEFAULT 1,
    enable_avif BOOLEAN DEFAULT 0,
    max_size INTEGER DEFAULT 5242880, -- 5MB default
    quota_mb INTEGER DEFAULT 100,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tokens (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    vado_id INTEGER NOT NULL,
    token TEXT NOT NULL UNIQUE,
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vado_id) REFERENCES vados(id)
);

CREATE TABLE IF NOT EXISTS uploads (
    id TEXT PRIMARY KEY, -- UUID
    vado_id INTEGER NOT NULL,
    original_name TEXT NOT NULL,
    file_size INTEGER NOT NULL,
    formats_json TEXT NOT NULL, -- JSON array of generated formats/paths
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vado_id) REFERENCES vados(id)
);

-- Seed initial data
INSERT INTO vados (name, enable_jpg, enable_webp, enable_avif) 
VALUES ('Default Vado', 1, 1, 1);

INSERT INTO tokens (vado_id, token) 
VALUES (1, 'chitra_test_token_123');
