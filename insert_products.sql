DELETE FROM products;
ALTER TABLE products AUTO_INCREMENT = 1;

INSERT INTO products (part_number, name, category, description, price, stock_quantity, supplier) VALUES
-- Brakes
('BRK-001', 'Front Brake Pads - Toyota Camry', 'Brakes', 'High-quality ceramic brake pads for Toyota Camry. Ensures smooth braking and reduced noise.', 95000, 25, 'TZ Auto Supplies'),
('BRK-002', 'Brake Discs Pair - Honda Civic', 'Brakes', 'Ventilated brake discs for Honda Civic. Better heat dissipation.', 180000, 15, 'TZ Auto Supplies'),
('BRK-003', 'Brake Master Cylinder', 'Brakes', 'Universal brake master cylinder. Fits most vehicles.', 220000, 8, 'Dar Auto Parts'),

-- Engine
('ENG-001', 'Synthetic Motor Oil 5W-30', 'Engine', '5L container of premium synthetic motor oil. Extended engine life.', 65000, 50, 'Shell Tanzania'),
('ENG-002', 'Engine Air Filter - Honda Civic', 'Engine', 'Replacement air filter for Honda Civic. Improves engine performance.', 35000, 40, 'TZ Auto Supplies'),
('ENG-003', 'Cabin Air Filter', 'Engine', 'Keeps cabin air clean and fresh. Fits most vehicles.', 28000, 35, 'Auto Care Tanzania'),
('ENG-004', 'Spark Plugs Set (4 pieces)', 'Engine', 'Iridium spark plugs for improved ignition. Set of 4.', 95000, 30, 'TZ Auto Supplies'),
('ENG-005', 'Oil Filter - Genuine', 'Engine', 'Premium oil filter for engine protection. Removes impurities.', 22000, 60, 'Shell Tanzania'),
('ENG-006', 'Water Pump Assembly', 'Engine', 'Complete water pump assembly. Maintains engine cooling.', 280000, 6, 'Dar Auto Parts'),
('ENG-007', 'Thermostat Housing', 'Engine', 'Maintains optimal engine temperature.', 45000, 12, 'Auto Care Tanzania'),

-- Electrical
('ELE-001', 'Car Battery 12V 600CCA', 'Electrical', 'Heavy-duty 12V battery with 600 cold cranking amps. Long lasting.', 350000, 20, 'Exide Tanzania'),
('ELE-002', 'Alternator - Toyota Corolla', 'Electrical', 'High-output alternator for Toyota Corolla. Ensures stable power supply.', 520000, 5, 'TZ Auto Supplies'),
('ELE-003', 'Starter Motor', 'Electrical', 'Heavy-duty starter motor for reliable engine starting.', 480000, 4, 'Dar Auto Parts'),
('ELE-004', 'Headlight Bulbs (Pair)', 'Electrical', 'Bright LED headlight bulbs for better night visibility.', 65000, 45, 'Auto Care Tanzania'),
('ELE-005', 'Tail Light Assembly', 'Electrical', 'Complete tail light assembly with bulbs included.', 85000, 18, 'TZ Auto Supplies'),
('ELE-006', 'Windscreen Wiper Blades', 'Electrical', 'Heavy-duty wiper blades for clear visibility in rain.', 28000, 80, 'Shell Tanzania'),

-- Filters
('FLT-001', 'Diesel Fuel Filter', 'Filters', 'Premium fuel filter for diesel engines. Superior filtration.', 32000, 50, 'Auto Care Tanzania'),
('FLT-002', 'Air Intake Filter', 'Filters', 'High-performance air intake filter for better airflow.', 38000, 40, 'TZ Auto Supplies'),
('FLT-003', 'Transmission Filter', 'Filters', 'Genuine transmission filter for automatic transmissions.', 55000, 15, 'Dar Auto Parts'),

-- Fluids
('FLD-001', 'Coolant/Antifreeze 1L', 'Fluids', 'Premium coolant protects engine from overheating. Long-life formula.', 42000, 60, 'Shell Tanzania'),
('FLD-002', 'Transmission Fluid ATF', 'Fluids', 'Automatic transmission fluid for smooth gear changes.', 58000, 35, 'Shell Tanzania'),
('FLD-003', 'Power Steering Fluid', 'Fluids', 'Hydraulic fluid for power steering systems.', 45000, 30, 'Auto Care Tanzania'),
('FLD-004', 'Brake Fluid DOT 4', 'Fluids', 'Premium brake fluid for reliable braking performance.', 35000, 50, 'Shell Tanzania'),

-- Ignition
('IGN-001', 'Ignition Coil Pack', 'Ignition', 'High-quality ignition coil for reliable spark generation.', 78000, 16, 'TZ Auto Supplies'),
('IGN-002', 'Distributor Cap and Rotor', 'Ignition', 'Complete distributor cap with rotor for proper ignition timing.', 52000, 12, 'Dar Auto Parts'),

-- Suspension
('SUS-001', 'Shock Absorber Pair', 'Suspension', 'Gas-filled shock absorbers for smooth ride comfort.', 320000, 8, 'TZ Auto Supplies'),
('SUS-002', 'Suspension Spring (per piece)', 'Suspension', 'Heavy-duty coil spring for vehicle suspension.', 185000, 10, 'Dar Auto Parts'),
('SUS-003', 'Tie Rod End Assembly', 'Suspension', 'Steering tie rod end for safe steering control.', 95000, 20, 'Auto Care Tanzania'),
('SUS-004', 'Control Arm Assembly', 'Suspension', 'Complete front control arm with bushings.', 280000, 6, 'TZ Auto Supplies'),
('SUS-005', 'Suspension Bushings (Set)', 'Suspension', 'Rubber bushings for suspension stability. Set of 4.', 68000, 25, 'Auto Care Tanzania'),
('SUS-006', 'Ball Joint Assembly', 'Suspension', 'Upper and lower ball joint for steering precision.', 125000, 14, 'Dar Auto Parts'),

-- Other
('OTH-001', 'Fan Belt/Serpentine Belt', 'Other', 'Multi-rib fan belt for engine accessory drive.', 52000, 35, 'Shell Tanzania'),
('OTH-002', 'Radiator Hose Kit', 'Other', 'Complete radiator hose kit (upper and lower).', 48000, 28, 'Auto Care Tanzania'),
('OTH-003', 'Engine Gasket Set', 'Other', 'Complete gasket set for engine overhaul.', 185000, 7, 'TZ Auto Supplies'),
('OTH-004', 'Door Hinge Assembly', 'Other', 'Durable door hinge with all fasteners included.', 125000, 12, 'Dar Auto Parts'),
('OTH-005', 'Side Mirror Assembly', 'Other', 'Electric side mirror with heating function.', 165000, 8, 'TZ Auto Supplies'),
('OTH-006', 'Floor Mats Set', 'Other', 'Rubber floor mats for interior protection. Set of 4.', 38000, 50, 'Auto Care Tanzania');
