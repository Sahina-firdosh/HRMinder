import os
import docx2txt
import PyPDF2
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import re
from pdfminer.high_level import extract_text
import mysql.connector

skills_list = [
    'Python', 'Data Analysis', 'Machine Learning', 'Communication', 'Project Management', 'Deep Learning', 'SQL', 'Tableau',
    'Java', 'C++', 'JavaScript', 'HTML', 'CSS', 'React', 'Angular', 'Node.js', 'MongoDB', 'Express.js', 'Git',
    'Research', 'Statistics', 'Quantitative Analysis', 'Qualitative Analysis', 'SPSS', 'R', 'Data Visualization', 'Matplotlib',
    'Seaborn', 'Plotly', 'Pandas', 'Numpy', 'Scikit-learn', 'TensorFlow', 'Keras', 'PyTorch', 'NLTK', 'Text Mining',
    'Natural Language Processing', 'Computer Vision', 'Image Processing', 'OCR', 'Speech Recognition', 'Recommendation Systems',
    'Collaborative Filtering', 'Content-Based Filtering', 'Reinforcement Learning', 'Neural Networks', 'Convolutional Neural Networks',
    'Recurrent Neural Networks', 'Generative Adversarial Networks', 'XGBoost', 'Random Forest', 'Decision Trees', 'Support Vector Machines',
    'Linear Regression', 'Logistic Regression', 'K-Means Clustering', 'Hierarchical Clustering', 'DBSCAN', 'Association Rule Learning',
    'Apache Hadoop', 'Apache Spark', 'MapReduce', 'Hive', 'HBase', 'Apache Kafka', 'Data Warehousing', 'ETL', 'Big Data Analytics',
    'Cloud Computing', 'Amazon Web Services (AWS)', 'Microsoft Azure', 'Google Cloud Platform (GCP)', 'Docker', 'Kubernetes', 'Linux',
    'Shell Scripting', 'Cybersecurity', 'Network Security', 'Penetration Testing', 'Firewalls', 'Encryption', 'Malware Analysis',
    'Digital Forensics', 'CI/CD', 'DevOps', 'Agile Methodology', 'Scrum', 'Kanban', 'Continuous Integration', 'Continuous Deployment',
    'Software Development', 'Web Development', 'Mobile Development', 'Backend Development', 'Frontend Development', 'Full-Stack Development',
    'UI/UX Design', 'Responsive Design', 'Wireframing', 'Prototyping', 'User Testing', 'Adobe Creative Suite', 'Photoshop', 'Illustrator',
    'InDesign', 'Figma', 'Sketch', 'Zeplin', 'InVision', 'Product Management', 'Market Research', 'Customer Development', 'Lean Startup',
    'Business Development', 'Sales', 'Marketing', 'Content Marketing', 'Social Media Marketing', 'Email Marketing', 'SEO', 'SEM', 'PPC',
    'Google Analytics', 'Facebook Ads', 'LinkedIn Ads', 'Lead Generation', 'Customer Relationship Management (CRM)', 'Salesforce',
    'HubSpot', 'Zendesk', 'Intercom', 'Customer Support', 'Technical Support', 'Troubleshooting', 'Ticketing Systems', 'ServiceNow',
    'ITIL', 'Quality Assurance', 'Manual Testing', 'Automated Testing', 'Selenium', 'JUnit', 'Load Testing', 'Performance Testing',
    'Regression Testing', 'Black Box Testing', 'White Box Testing', 'API Testing', 'Mobile Testing', 'Usability Testing', 'Accessibility Testing',
    'Cross-Browser Testing', 'Agile Testing', 'User Acceptance Testing', 'Software Documentation', 'Technical Writing', 'Copywriting',
    'Editing', 'Proofreading', 'Content Management Systems (CMS)', 'WordPress', 'Joomla', 'Drupal', 'Magento', 'Shopify', 'E-commerce',
    'Payment Gateways', 'Inventory Management', 'Supply Chain Management', 'Logistics', 'Procurement', 'ERP Systems', 'SAP', 'Oracle',
    'Microsoft Dynamics', 'Tableau', 'Power BI', 'QlikView', 'Looker', 'Data Warehousing', 'ETL', 'Data Engineering', 'Data Governance',
    'Data Quality', 'Master Data Management', 'Predictive Analytics', 'Prescriptive Analytics', 'Descriptive Analytics', 'Business Intelligence',
    'Dashboarding', 'Reporting', 'Data Mining', 'Web Scraping', 'API Integration', 'RESTful APIs', 'GraphQL', 'SOAP', 'Microservices',
    'Serverless Architecture', 'Lambda Functions', 'Event-Driven Architecture', 'Message Queues', 'GraphQL', 'Socket.io', 'WebSockets'
'Ruby', 'Ruby on Rails', 'PHP', 'Symfony', 'Laravel', 'CakePHP', 'Zend Framework', 'ASP.NET', 'C#', 'VB.NET', 'ASP.NET MVC', 'Entity Framework',
    'Spring', 'Hibernate', 'Struts', 'Kotlin', 'Swift', 'Objective-C', 'iOS Development', 'Android Development', 'Flutter', 'React Native', 'Ionic',
    'Mobile UI/UX Design', 'Material Design', 'SwiftUI', 'RxJava', 'RxSwift', 'Django', 'Flask', 'FastAPI', 'Falcon', 'Tornado', 'WebSockets',
    'GraphQL', 'RESTful Web Services', 'SOAP', 'Microservices Architecture', 'Serverless Computing', 'AWS Lambda', 'Google Cloud Functions',
    'Azure Functions', 'Server Administration', 'System Administration', 'Network Administration', 'Database Administration', 'MySQL', 'PostgreSQL',
    'SQLite', 'Microsoft SQL Server', 'Oracle Database', 'NoSQL', 'MongoDB', 'Cassandra', 'Redis', 'Elasticsearch', 'Firebase', 'Google Analytics',
    'Google Tag Manager', 'Adobe Analytics', 'Marketing Automation', 'Customer Data Platforms', 'Segment', 'Salesforce Marketing Cloud', 'HubSpot CRM',
    'Zapier', 'IFTTT', 'Workflow Automation', 'Robotic Process Automation (RPA)', 'UI Automation', 'Natural Language Generation (NLG)',
    'Virtual Reality (VR)', 'Augmented Reality (AR)', 'Mixed Reality (MR)', 'Unity', 'Unreal Engine', '3D Modeling', 'Animation', 'Motion Graphics',
    'Game Design', 'Game Development', 'Level Design', 'Unity3D', 'Unreal Engine 4', 'Blender', 'Maya', 'Adobe After Effects', 'Adobe Premiere Pro',
    'Final Cut Pro', 'Video Editing', 'Audio Editing', 'Sound Design', 'Music Production', 'Digital Marketing', 'Content Strategy', 'Conversion Rate Optimization (CRO)',
    'A/B Testing', 'Customer Experience (CX)', 'User Experience (UX)', 'User Interface (UI)', 'Persona Development', 'User Journey Mapping', 'Information Architecture (IA)',
    'Wireframing', 'Prototyping', 'Usability Testing', 'Accessibility Compliance', 'Internationalization (I18n)', 'Localization (L10n)', 'Voice User Interface (VUI)',
    'Chatbots', 'Natural Language Understanding (NLU)', 'Speech Synthesis', 'Emotion Detection', 'Sentiment Analysis', 'Image Recognition', 'Object Detection',
    'Facial Recognition', 'Gesture Recognition', 'Document Recognition', 'Fraud Detection', 'Cyber Threat Intelligence', 'Security Information and Event Management (SIEM)',
    'Vulnerability Assessment', 'Incident Response', 'Forensic Analysis', 'Security Operations Center (SOC)', 'Identity and Access Management (IAM)', 'Single Sign-On (SSO)',
    'Multi-Factor Authentication (MFA)', 'Blockchain', 'Cryptocurrency', 'Decentralized Finance (DeFi)', 'Smart Contracts', 'Web3', 'Non-Fungible Tokens (NFTs)']


education_list = [
        'Computer Science', 'Information Technology', 'Software Engineering', 'Electrical Engineering', 'Mechanical Engineering', 'Civil Engineering',
        'Chemical Engineering', 'Biomedical Engineering', 'Aerospace Engineering', 'Nuclear Engineering', 'Industrial Engineering', 'Systems Engineering',
        'Environmental Engineering', 'Petroleum Engineering', 'Geological Engineering', 'Marine Engineering', 'Robotics Engineering', 'Biotechnology',
        'Biochemistry', 'Microbiology', 'Genetics', 'Molecular Biology', 'Bioinformatics', 'Neuroscience', 'Biophysics', 'Biostatistics', 'Pharmacology',
        'Physiology', 'Anatomy', 'Pathology', 'Immunology', 'Epidemiology', 'Public Health', 'Health Administration', 'Nursing', 'Medicine', 'Dentistry',
        'Pharmacy', 'Veterinary Medicine', 'Medical Technology', 'Radiography', 'Physical Therapy', 'Occupational Therapy', 'Speech Therapy', 'Nutrition',
        'Sports Science', 'Kinesiology', 'Exercise Physiology', 'Sports Medicine', 'Rehabilitation Science', 'Psychology', 'Counseling', 'Social Work',
        'Sociology', 'Anthropology', 'Criminal Justice', 'Political Science', 'International Relations', 'Economics', 'Finance', 'Accounting', 'Business Administration',
        'Management', 'Marketing', 'Entrepreneurship', 'Hospitality Management', 'Tourism Management', 'Supply Chain Management', 'Logistics Management',
        'Operations Management', 'Human Resource Management', 'Organizational Behavior', 'Project Management', 'Quality Management', 'Risk Management',
        'Strategic Management', 'Public Administration', 'Urban Planning', 'Architecture', 'Interior Design', 'Landscape Architecture', 'Fine Arts',
        'Visual Arts', 'Graphic Design', 'Fashion Design', 'Industrial Design', 'Product Design', 'Animation', 'Film Studies', 'Media Studies',
        'Communication Studies', 'Journalism', 'Broadcasting', 'Creative Writing', 'English Literature', 'Linguistics', 'Translation Studies',
        'Foreign Languages', 'Modern Languages', 'Classical Studies', 'History', 'Archaeology', 'Philosophy', 'Theology', 'Religious Studies',
        'Ethics', 'Education', 'Early Childhood Education', 'Elementary Education', 'Secondary Education', 'Special Education', 'Higher Education',
        'Adult Education', 'Distance Education', 'Online Education', 'Instructional Design', 'Curriculum Development'
        'Library Science', 'Information Science', 'Computer Engineering', 'Software Development', 'Cybersecurity', 'Information Security',
        'Network Engineering', 'Data Science', 'Data Analytics', 'Business Analytics', 'Operations Research', 'Decision Sciences',
        'Human-Computer Interaction', 'User Experience Design', 'User Interface Design', 'Digital Marketing', 'Content Strategy',
        'Brand Management', 'Public Relations', 'Corporate Communications', 'Media Production', 'Digital Media', 'Web Development',
        'Mobile App Development', 'Game Development', 'Virtual Reality', 'Augmented Reality', 'Blockchain Technology', 'Cryptocurrency',
        'Digital Forensics', 'Forensic Science', 'Criminalistics', 'Crime Scene Investigation', 'Emergency Management', 'Fire Science',
        'Environmental Science', 'Climate Science', 'Meteorology', 'Geography', 'Geomatics', 'Remote Sensing', 'Geoinformatics',
        'Cartography', 'GIS (Geographic Information Systems)', 'Environmental Management', 'Sustainability Studies', 'Renewable Energy',
        'Green Technology', 'Ecology', 'Conservation Biology', 'Wildlife Biology', 'Zoology']



# drive_path = "/content/drive/MyDrive/Minor Project/ML/Resumes"

# to extract text from pdf file
def extract_text_from_pdf(file_path):
    text = ""
    with open(file_path, 'rb') as file:
        reader = PyPDF2.PdfReader(file)

        for page in reader.pages:
            text += page.extract_text()
    return text

# to extract text from docx(word file)
def extract_text_from_docx(file_path):
    return docx2txt.process(file_path)

# to extract text from tet file
def extract_text_from_txt(file_path):
    with open(file_path, 'r', encoding='utf-8') as file:
        return file.read()

# to extract text from one of the file types
def extract_text(file_path):
    if file_path.endswith('.pdf'):
        return extract_text_from_pdf(file_path)
    elif file_path.endswith('.docx'):
        return extract_text_from_docx(file_path)
    elif file_path.endswith('.txt'):
        return extract_text_from_txt(file_path)
    else:
        return ""


# to Extract Name from Resume
def extract_name(text):
  name = None
  pattern = r"(\b[A-Z][a-z]+\b)\s(\b[A-Z][a-z]+\b)"
  match = re.search(pattern, text)
  if match:
        name = match.group()
  return name

# to Extract Phone number from Resume
def extract_phone_number(text):
    pno= None
    pattern = r"\b(?:\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}\b"
    match = re.search(pattern, text)
    if match:
        pno = match.group()
    return pno

# to Extract email id from Resume
def extract_email_id(text):
  e_id = None
  pattern = r"\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,7}\b"
  match = re.search(pattern, text)
  if match:
    e_id = match.group()
  return e_id

# to Extract skills from Resume
def extract_skills(text):
  skills = []
  for skill in skills_list:
    pattern = r"\b{}\b".format(re.escape(skill))
    match = re.search(pattern, text, re.IGNORECASE)
    if match:
      skills.append(skill)
  return skills

# to Extract Education from Resume
def extract_education(text):
  education = []
  for education_keyword in education_list:
     pattern = r"(?i)\b{}\b".format(re.escape(education_keyword))
     match = re.search(pattern, text)
     if match:
            education.append(match.group())
  return education



def process_resumes(job_description, resume_files):
    # Extract text from resumes
    resumes = []
    # resume_files = os.listdir(resume_folder)
    for file_path in resume_files:
        # file_path = os.path.join(resume_folder, resume_file)
        resumes.append(extract_text(file_path))

    if not resumes or not job_description:
        return [], []

    # Vectorize job description and resumes using TF-IDF
    vectorizer = TfidfVectorizer().fit_transform([job_description] + resumes)
    vectors = vectorizer.toarray()

    # Calculate cosine similarities
    job_vector = vectors[0]
    resume_vectors = vectors[1:]
    similarities = cosine_similarity([job_vector], resume_vectors)[0]

    # Get top 3 resumes and their similarity scores
    top_indices = similarities.argsort()[-5:][::-1]
    top_resumes = [resume_files[i] for i in top_indices]
    # similarity_scores = [round(similarities[i], 2) for i in top_indices]
    similarity_scores = [round(similarities[i] * 10, 1) for i in top_indices]

    db_connection = mysql.connector.connect( host="localhost", user="root", password="", database="hrm_db")
    cursor = db_connection.cursor()
    for i, (resume_file, score) in enumerate(zip(top_resumes, similarity_scores)):
        if score >= 0:
            resume_text = extract_text( resume_file)
            candidate_name = extract_name(resume_text)
            phone_number = extract_phone_number(resume_text)
            email_id = extract_email_id(resume_text)
            skills = extract_skills(resume_text)
            education = extract_education(resume_text)

             # Convert lists to strings
            skills_str = ', '.join(skills) if isinstance(skills, list) else skills
            education_str = ', '.join(education) if isinstance(education, list) else education

            # Insert data into resume_screening_tb
            insert_query = """
                INSERT INTO resume_screening_tb (candidate_name, email_id, phone_number, skills, education)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(insert_query, (candidate_name, email_id, phone_number, skills_str, education_str))
            db_connection.commit()  # Commit changes

    # Close database connection
    cursor.close()
    db_connection.close()
    return top_resumes, similarity_scores


# Example usage:

# job_description = "Uses existing and emerging technology platforms to design, develop and document technically detailed applications. S/he writes new complex systems (a grouping of programs involving multiple levels of table dimension and internal sorts that accomplishes a particular new function), designs and codes programs and creates test transactions. S/he also analyzes organizational needs and goals to develop and implement application systems. Provides application software development services or technical support in situations of moderate complexity. May also be responsible for requirements gathering and BRD/SRD preparation. Has thorough knowledge of the Software Development Life Cycle. Conducts reviews of the test Plan and test Data.Programming well-designed, testable, efficient code. Analyze, design and develop tests and test-automation suites. Develop flowcharts, layouts and documentation to satisfy requirements and solutions. Maintain software functionality and currency (technical debt and gain). Actively participate in code reviews. Integrate software components into a fully functional software system. Apply security and privacy principles.Execute full lifecycle software development. Develop software verification plans and quality assurance procedures. Troubleshoot, debug and upgrade existing systems. Ensure software is updated with latest features. Participate in deployment process following all change controls. Provide ongoing maintenance, support and enhancements in existing systems and platforms. Provide recommendations for continuous improvement.Active learning engagement. Complete all required mandatory training / policy awareness curricula on time. Use learning tools such as Pluralsight to complete both recommended and aspirational targets set in personal development plans.Demonstrate team work. Leverage existing products/functionality and promote reuse. Work alongside other engineers on the team to elevate technology and consistently apply best practices. Collaborate closely with all the other members of the team to take shared responsibility for the overall efforts that the team has committed to. Collaborate cross-functionally with data engineers, business users, project managers and other engineers to achieve elegant solutions. Utilize local meetups to gain and share knowledge.Compiles documentation written by more senior developers of all procedures used in system. Acts as mentor to junior level engineers. Experience in using a specific application development toolkit and knowkedge of front end and backend development coding languages such as C#, Java, HTML, NodeJS, CSS, JSON, Angular, JavaScript. Must also have knowledge in application frameworks and containerization. Team work and organization skillsContributes to the achievement of area objectivesMODIFIED BASED UPON LOCAL REGULATIONS/REQUIREMENTSBachelor's degree in computer science engineering or a related discipline, or equivalent work experience required2-6 years of experience in software development required; experience in the securities or financial services industry is a plus; should have thorough knowledge of the software development cycle S/he must also have experience developing Front-End and Back-end. Job holder must be knowledgeable about cross-platform interoperability (multiple platforms i.e. NT, Intranet, etc.) , major tools in a toolkit for a specific platform and features of multiple toolkits. S/he must be experienced at resolving hardware, software, and communications malfunctions and understand the business impact of resolving complications.. BNY Mellon is an Equal Employment Opportunity/Affirmative Action Employer. Minorities/Females/Individuals with Disabilities/Protected Veterans. Our ambition is to build the best global team – one that is representative and inclusive of the diverse talent, clients and communities we work with and serve – and to empower our team to do their best work. We support wellbeing and a balanced life, and offer a range of family-friendly, inclusive employment policies and employee forums."
# job_description = "BNY Mellon is looking for a Software Engineer to design, develop, and maintain applications using both established and emerging technologies. The ideal candidate will possess 2-6 years of experience in software development, particularly within the securities or financial services industry, with strong knowledge of the Software Development Life Cycle (SDLC) and experience in front-end and back-end development. Responsibilities include writing efficient, well-designed code for complex systems, creating test automation suites, and integrating software components into fully functional systems. The role involves analyzing organizational needs to develop application solutions, maintaining software functionality, performing upgrades, and ensuring adherence to security and privacy standards. This engineer will collaborate with cross-functional teams, mentor junior engineers, conduct code reviews, and promote best practices in development. Technical expertise in languages and tools such as C#, Java, HTML, NodeJS, CSS, JSON, Angular, and JavaScript is required, along with familiarity in application frameworks, containerization, and troubleshooting across platforms. BNY Mellon values continuous improvement, encouraging ongoing learning through tools like Pluralsight, and is committed to fostering a diverse and inclusive work environment, welcoming candidates of all backgrounds and offering equal employment opportunities."
# job_description = "BNY Mellon seeks a skilled Project Manager to lead and oversee complex projects, ensuring they align with organizational goals and deliver value. The Project Manager will be responsible for planning, executing, and closing projects, managing timelines, budgets, and resources while coordinating with cross-functional teams. This role requires strong experience in project management methodologies (Agile, Waterfall, etc.) and a deep understanding of the Software Development Life Cycle (SDLC). The Project Manager will facilitate clear communication, define project scope and objectives, manage risks, and ensure deliverables meet quality standards. They will also conduct regular progress reviews, provide detailed status reports, and engage stakeholders at various levels. In addition, this position involves mentoring team members, implementing best practices, and continuously improving project management processes. Ideal candidates should have 5-8 years of experience, preferably in the financial services sector, with proficiency in project management tools, excellent problem-solving abilities, and strong interpersonal and organizational skills. BNY Mellon is committed to creating an inclusive workplace and values diversity, welcoming individuals of all backgrounds, including minorities, women, individuals with disabilities, and protected veterans."

# for nurse in healthcare
# job_description = '''We are looking for a Software Engineer to join our team at TechCorp. In this role, you will be responsible for developing and maintaining web applications using Python and JavaScript. You will collaborate with cross-functional teams to design and implement new features, ensuring they meet business needs and enhance the user experience. Your work will involve creating and managing RESTful APIs to enable seamless integration with external systems, as well as optimizing database performance to improve efficiency. Additionally, you will utilize Git for version control and Docker for containerizing applications, ensuring smooth deployment processes. The ideal candidate should have a strong foundation in Python, JavaScript, and Java, and experience working with frameworks like Django and React. A degree in Computer Science or a related field is required, along with familiarity with modern development tools and practices. Experience with Agile methodologies and cloud platforms is preferred.'''
# resume_folder = "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\"
# resume_files = [
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\Software Engineer.pdf",
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\Project Manager.pdf",
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\advocate.txt",
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\Data Scientist.pdf",
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\Finantial Analist.pdf",
    # "C:\\xampp\\htdocs\\Minor_project\\main\\resume_folder\\Healthcare.txt",
# ]

# top_resumes, similarity_scores = process_resumes(job_description, resume_files)

# # Print top matching resumes and similarity scores
# print("Resumes Scores from 1-10")
# for resume, score in zip(top_resumes, similarity_scores):
#     print(f"Resume: {resume}, Similarity Score: {score}")

# print("--------------------------------------------------------------------")
# print("Top matching resumes(Score >= 5)")
# for resume, score in zip(top_resumes, similarity_scores):
#   if (score >= 0):
#       resume_text = extract_text(os.path.join(resume_folder, resume))
#       # print(resume_text)
#       resume_name = extract_name(resume_text)
#       resume_phone_number = extract_phone_number(resume_text)
#       resume_email_id = extract_email_id(resume_text)
#       resume_skills = extract_skills(resume_text)
#       resume_education = extract_education(resume_text)

#       print("Name: ",resume_name)
#       print("Phone Number: ",resume_phone_number)
#       print("Email ID: ",resume_email_id)
#       print("Skills: ",resume_skills)
#       print("Education: ",resume_education)
#       print("Similarity Score: ", score)






