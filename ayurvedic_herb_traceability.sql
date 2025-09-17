
-- Ayurvedic Herb Traceability System Schema

-- Farmer Table
CREATE TABLE farmer (
    farmer_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(50),
    location VARCHAR(255)
);

-- Farmer Batch Table
CREATE TABLE farmer_batch (
    batch_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    farmer_id INT REFERENCES farmer(farmer_id),
    farm_name VARCHAR(100),
    farm_location VARCHAR(255),
    crop_type VARCHAR(50),
    harvest_date TIMESTAMP,
    quality_check VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Processor Table
CREATE TABLE processor (
    processor_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(50),
    location VARCHAR(255)
);

-- Processor Batch Table
CREATE TABLE processor_batch (
    process_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_id UUID REFERENCES farmer_batch(batch_id),
    processor_id INT REFERENCES processor(processor_id),
    process_type VARCHAR(50),
    facility_name VARCHAR(100),
    process_date TIMESTAMP,
    quality_check VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Distributor Table
CREATE TABLE distributor (
    distributor_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(50),
    location VARCHAR(255)
);

-- Distribution Batch Table
CREATE TABLE distribution_batch (
    distribution_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_id UUID REFERENCES farmer_batch(batch_id),
    distributor_id INT REFERENCES distributor(distributor_id),
    transport_mode VARCHAR(50),
    dispatch_date TIMESTAMP,
    arrival_date TIMESTAMP,
    storage_conditions VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Retailer Table
CREATE TABLE retailer (
    retailer_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact VARCHAR(50),
    location VARCHAR(255)
);

-- Retailer Batch Table
CREATE TABLE retailer_batch (
    retail_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_id UUID REFERENCES farmer_batch(batch_id),
    retailer_id INT REFERENCES retailer(retailer_id),
    store_name VARCHAR(100),
    arrival_date TIMESTAMP,
    shelf_life VARCHAR(50),
    packaging_date TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Batch Journey Table (log of all events)
CREATE TABLE batch_journey (
    journey_id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_id UUID REFERENCES farmer_batch(batch_id),
    stage VARCHAR(50),
    stage_id INT,
    event_time TIMESTAMP,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
