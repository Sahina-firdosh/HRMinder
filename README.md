# HRMinder
HRMinder is a web-based HR management platform designed to streamline human resources processes through automation and machine learning. Key features include employee data management, report generation, attrition prediction, and AI-powered resume screening to enhance recruitment efficiency.

## Features
**Employee Data Management:** Add, update, and delete employee information with ease.
**Report Generation:** Generate pre-defined HR reports with a single click.
**Attrition Prediction:** Utilizes a machine learning model trained and tested on 15,000 data points to predict employee attrition based on factors like salary, last promotion, and job satisfaction.
**AI-Driven Resume Screening:** Uses natural language processing (NLP) to screen resumes against job descriptions, providing a similarity score and extracting relevant information for HR review.

## Tech Stack
**Front-End:** HTML, CSS, JavaScript
**Back-End:** Python, MySQL, PHP
**Machine Learning:** Python (scikit-learn, TensorFlow, RandomForestClassifier for attrition prediction)
**Data Analysis & Processing:** Numpy, Pandas, Matplotlib, SQLAlchemy (for database connectivity), pymysql
**NLP for Resume Screening:** TfidfVectorizer and cosine similarity (for job description matching and resume parsing)
