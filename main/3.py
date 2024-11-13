# Import necessary libraries
import pandas as pd
import numpy as np
from sqlalchemy import create_engine
from sklearn.preprocessing import LabelEncoder, StandardScaler
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report, precision_score
import joblib
import pymysql

# Step 1: Train the Model on the CSV Data
# Load and preprocess the training data
df = pd.read_csv("employee_attrition_data.csv")
df = df.drop_duplicates()

# Check the columns to confirm what is available
print("Columns in training data:", df.columns)

# Safely drop unnecessary columns
df = df.drop(columns=['number_project', 'last_evaluation',
             'Work_accident'], errors='ignore')

# Define salary mapping
salary_mapping = {
    'Less than 30k': 'low',
    'Between 30k and 50k': 'medium',
    'Between 50k and 70k': 'medium',
    'Between 70k and 1L': 'high',
    'More than 1L': 'high'
}

# Apply salary mapping and encode categorical variables
df['salary'] = df['salary'].map(salary_mapping)
label_encoder = LabelEncoder()
df['salary'] = label_encoder.fit_transform(df['salary'])
df['Department'] = label_encoder.fit_transform(df['Department'])

# Define features and target variable
X = df.drop('left', axis=1)
y = df['left']

# Split data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.2, random_state=42)

# Scaling features
scaler = StandardScaler()
X_train = scaler.fit_transform(X_train)
X_test = scaler.transform(X_test)

# Train the RandomForest model
model = RandomForestClassifier(random_state=42)
model.fit(X_train, y_train)

# Save the trained model and scaler
joblib.dump(model, 'random_forest_model.pkl')
joblib.dump(scaler, 'scaler.pkl')
joblib.dump(label_encoder, 'label_encoder.pkl')

# Step 2: Evaluate the Model
y_pred = model.predict(X_test)

print('Model Accuracy:', accuracy_score(y_test, y_pred))
print('Model Precision:', precision_score(y_test, y_pred))
print(classification_report(y_test, y_pred))

# Step 3: Connect to the Database and Fetch Data
# Database connection details
db_user = "root"
db_password = ""  # Update if you have a password
db_host = "localhost"
db_name = "hrm_db"

# Connect to MySQL database
try:
    engine = create_engine(
        f"mysql+pymysql://{db_user}:{db_password}@{db_host}/{db_name}")
    with engine.connect() as connection:
        print("Successfully connected to the database.")

    # SQL Query using the provided command
    query = """
    SELECT 
        j.employee_id,
        s.emp_name,
        s.department,
        s.avg_monthly_hours,
        s.promotion_in_5_years,
        s.employee_salary AS monthly_compensation,
        s.role_satisfaction,
        j.Date_of_joining,
        TIMESTAMPDIFF(YEAR, STR_TO_DATE(j.Date_of_joining, '%d.%m.%Y'), CURDATE()) AS time_spend_company 
    FROM 
        jims_emp_data_tb j
    JOIN 
        survey_feedback_tb s ON j.employee_id = s.employee_id;
    """

    # Load the data from the database using pandas
    df_feedback = pd.read_sql(query, con=engine)
    print("Data successfully fetched from the database.")

except Exception as e:
    print("Error connecting to the database:", e)
    exit()

# Step 4: Preprocess the Feedback Data
# Map the salary in the feedback dataset to the training dataset's salary categories
df_feedback['monthly_compensation'] = df_feedback['monthly_compensation'].map(
    salary_mapping)
df_feedback['monthly_compensation'] = df_feedback['monthly_compensation'].fillna(
    'low')

# Encode 'monthly_compensation' and 'department'
df_feedback['salary'] = df_feedback['monthly_compensation'].apply(
    lambda x: 'low' if x not in salary_mapping.values() else x)
df_feedback['salary'] = label_encoder.transform(df_feedback['salary'])
df_feedback['Department'] = label_encoder.fit_transform(
    df_feedback['department'])

# Define features for prediction
features = ['avg_monthly_hours', 'promotion_in_5_years',
            'salary', 'role_satisfaction', 'time_spend_company']
X_feedback = df_feedback[features]

# Load the scaler and model
scaler = joblib.load('scaler.pkl')
X_feedback_scaled = scaler.transform(X_feedback)
model = joblib.load('random_forest_model.pkl')

# Step 5: Predict Attrition
predictions = model.predict(X_feedback_scaled)
df_feedback['prediction'] = predictions
df_feedback['prediction'] = df_feedback['prediction']
# Step 6: Save Predictions to the Database
try:
    with engine.connect() as conn:
       
        # Insert predictions using named parameters
        insert_query = """
            INSERT INTO survey_feedback_tb (employee_id, emp_name, department, prediction)
            VALUES (:employee_id, :emp_name, :department, :prediction)
        """

        # Loop through each row and insert data with a dictionary
        for _, row in df_feedback.iterrows():
            row_data = {
                "employee_id": int(row['employee_id']) if pd.notnull(row['employee_id']) else None,
                "emp_name": str(row['emp_name']) if pd.notnull(row['emp_name']) else None,
                "department": str(row['department']) if pd.notnull(row['department']) else None,
                "prediction": str(row['prediction']) if pd.notnull(row['prediction']) else None
            }
            conn.execute(insert_query, row_data)

    print("Predictions completed and saved to the database.")

except Exception as e:
    print("Error saving predictions to the database:", e)
