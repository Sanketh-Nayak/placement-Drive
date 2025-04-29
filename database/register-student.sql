CREATE TABLE IF NOT EXISTS student_registration (
    id INT AUTO_INCREMENT PRIMARY KEY,
    -- Personal Information
    first_name VARCHAR(50),
    middle_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100),
    contact_number VARCHAR(15),
    dob DATE,
    gender VARCHAR(10),
    aadhar_number VARCHAR(12),
    -- Address Information
    house_number VARCHAR(100),
    area_village VARCHAR(200),
    city VARCHAR(100),
    district VARCHAR(100),
    state VARCHAR(100),
    pin_code VARCHAR(10),
    landmark VARCHAR(200),
    -- Academic Information
    college_name VARCHAR(200),
    course_name VARCHAR(100),
    year_of_passout VARCHAR(4),
    cgpa VARCHAR(10),
    sslc_marks VARCHAR(10),
    puc_marks VARCHAR(10),
    -- Professional Experience and Internship
    has_experience VARCHAR(5),
    internships TEXT,
    company_name VARCHAR(200),
    -- Document Details
    aadhar_path VARCHAR(255),
    pan_path VARCHAR(255),
    photo_path VARCHAR(255),
    signature_path VARCHAR(255),
    resume_path VARCHAR(255),
    certificate_path VARCHAR(255),

    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
