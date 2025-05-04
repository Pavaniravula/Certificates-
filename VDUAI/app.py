from flask import Flask, request, render_template, send_file
from werkzeug.utils import secure_filename
from moviepy.editor import VideoFileClip, AudioFileClip
import os
import speech_recognition as sr
from langdetect import detect
from translate import Translator
from gtts import gTTS

app = Flask(__name__)  # Corrected here
UPLOAD_FOLDER = 'uploads'
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

def extract_audio(video_file, audio_file):
    try:
        video = VideoFileClip(video_file)
        audio = video.audio
        audio.write_audiofile(audio_file, codec='pcm_s16le')
        return True
    except Exception as e:
        print(f"Error extracting audio: {e}")
        return False

def transcribe_audio(audio_file):
    recognizer = sr.Recognizer()
    with sr.AudioFile(audio_file) as source:
        audio_data = recognizer.record(source)
    transcript = recognizer.recognize_sphinx(audio_data)
    detected_language = detect(transcript)
    return transcript, detected_language

def translate_text(text, target_language='te'):
    translator = Translator(to_lang=target_language)
    return translator.translate(text)

def text_to_speech(text, output_file, lang='te', gender='male'):
    tts = gTTS(text=text, lang=lang)
    tts.save(output_file)
    if gender == 'male':
        os.system(f'sox {output_file} -pitch -100')  # Optional male pitch effect
    return

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/upload', methods=['POST'])
def upload_video():
    if 'video' not in request.files:
        return 'No file part', 400
    video = request.files['video']
    gender = request.form.get('gender', 'male')
    target_language = request.form.get('language', 'te')
    if video.filename == '':
        return 'No selected file', 400
    if video:
        if not os.path.exists(UPLOAD_FOLDER):
            os.makedirs(UPLOAD_FOLDER)
        filename = secure_filename(video.filename)
        video_path = os.path.join(app.config['UPLOAD_FOLDER'], filename)
        video.save(video_path)

        audio_file = os.path.join(app.config['UPLOAD_FOLDER'], f'{os.path.splitext(filename)[0]}.wav')
        if not extract_audio(video_path, audio_file):
            return 'Error extracting audio from video', 500

        transcript, detected_language = transcribe_audio(audio_file)
        translated_text = translate_text(transcript, target_language=target_language)

        tts_audio_file = os.path.join(app.config['UPLOAD_FOLDER'], f'{os.path.splitext(filename)[0]}_translated.mp3')
        text_to_speech(translated_text, tts_audio_file, lang=target_language, gender=gender)

        video_clip = VideoFileClip(video_path)
        translated_audio_clip = AudioFileClip(tts_audio_file)
        video_clip_with_translated_audio = video_clip.set_audio(translated_audio_clip)

        output_video_path = os.path.join(app.config['UPLOAD_FOLDER'], f'{os.path.splitext(filename)[0]}_translated.mp4')
        video_clip_with_translated_audio.write_videofile(output_video_path, codec='libx264', audio_codec='aac')

        response = f'''
            <div class="output-container">
                <p>Video Successfully Uploaded.</p>
                <p>Audio Extracted: {audio_file}</p>
                <p>Text Generated: {transcript}</p>
                <p>Translated Text: {translated_text}</p>
                <audio class="output-audio" controls><source src="/play_audio?audio_file={tts_audio_file}" type="audio/mp3">Your browser does not support the audio element.</audio>
                <video class="output-video" controls><source src="/play_video?video_file={output_video_path}" type="video/mp4">Your browser does not support the video element.</video>
                <p><a href="/download_video?video_file={output_video_path}">Download Translated Video</a></p>
            </div>
        '''
        return response

@app.route('/play_audio')
def play_audio():
    audio_file = request.args.get('audio_file')
    return send_file(audio_file)

@app.route('/play_video')
def play_video():
    video_file = request.args.get('video_file')
    return send_file(video_file)

@app.route('/download_video')
def download_video():
    video_file = request.args.get('video_file')
    return send_file(video_file, as_attachment=True)

if __name__ == '__main__':  # Corrected here
    app.run(debug=True)
transcript = recognizer.recognize_google(audio_data)  # Requires Internet
os.system(f'sox {output_file} -pitch -100')
return send_file(audio_file, mimetype='audio/mpeg')
from flask import send_from_directory

@app.route('/uploads/<filename>')
def uploaded_file(filename):
    return send_from_directory(app.config['UPLOAD_FOLDER'], filename)
<audio controls src="/uploads/{{ audio_file }}"></audio>
<video controls src="/uploads/{{ video_file }}"></video>
<!DOCTYPE html>
<html>
<head><title>AI Video Dubbing</title></head>
<body>
  <h2>Upload Video for AI Dubbing</h2>
  <form action="/upload" method="POST" enctype="multipart/form-data">
    <label>Upload Video:</label><br>
    <input type="file" name="video" accept="video/*" required><br><br>
    <label>Target Language (e.g., te, hi, fr):</label><br>
    <input type="text" name="language" value="te"><br><br>
    <label>Select Voice Gender:</label><br>
    <select name="gender">
      <option value="male">Male</option>
      <option value="female">Female</option>
    </select><br><br>
    <button type="submit">Upload</button>
  </form>
</body>
</html>
