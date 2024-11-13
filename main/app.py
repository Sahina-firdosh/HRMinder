from flask import Flask, request, jsonify
from werkzeug.utils import secure_filename
import os
from resume_screening import process_resumes  

app = Flask(__name__)

UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# Ensure the upload directory exists
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

@app.route('/process', methods=['POST'])
def process():
    data = request.get_json()  
    job_description = data.get("job_description")
    uploaded_files = request.files.getlist("resume_files")
    

    if not job_description or not uploaded_files:
        return jsonify({"error": "Job description and resume folder path are required"}), 400


    resume_paths = []
    for uploaded_file in uploaded_files:
        if uploaded_file:
            filename = secure_filename(uploaded_file.filename)
            file_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
            uploaded_file.save(file_path)
            resume_paths.append(file_path)
    

    # Call your resume processing function (assuming 'process_resumes' handles the file)
    top_resumes, similarity_scores = process_resumes(job_description, resume_paths)

    # Prepare the response data
    response_data = [{"resume": resume, "score": score} for resume, score in zip(top_resumes, similarity_scores)]
    
    return jsonify(response_data), 200

if __name__ == "__main__":
    app.run(debug=True, port=5001)
