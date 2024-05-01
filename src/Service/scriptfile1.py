import pytesseract
from PIL import Image
import sys
import json
import re

# Path to your Tesseract executable (change this if necessary)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

def extract_id_number(image_path):
    try:
        # Open the image using PIL (Python Imaging Library)
        with Image.open(image_path) as img:
            # Use pytesseract to extract text from the image
            text = pytesseract.image_to_string(img)
            # Search for ID numbers starting with 0 or 1 and consisting of 8 digits
            id_numbers = re.findall(r'\b[01]\d{7}\b', text)

            print(json.dumps({'id_numbers': id_numbers}))  # Print the output
    except Exception as e:
        print(f"Error: {e}")
        print(json.dumps({'id_numbers': []}))  # Print an empty list in case of error

if __name__ == "__main__":
    image_path = sys.argv[1]
    extract_id_number(image_path)



    
# import cv2
# import pytesseract
# import json
# import re
# from PIL import Image
# import sys
# import numpy as np
# # Path to your Tesseract executable (change this if necessary)
# pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# def extract_id_number(image_path):
#     try:
#         # Open the image using PIL (Python Imaging Library)
#         with Image.open(image_path) as img:
#             # Convert the image to OpenCV format
#             frame = cv2.cvtColor(np.array(img), cv2.COLOR_RGB2BGR)

#             # Convert the frame to grayscale
#             gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)

#             # Apply adaptive thresholding to enhance contrast
#             enhanced = cv2.adaptiveThreshold(gray, 255, cv2.ADAPTIVE_THRESH_MEAN_C, cv2.THRESH_BINARY, 11, 2)

#             # Use pytesseract to extract text from the enhanced image
#             text = pytesseract.image_to_string(enhanced)

#             # Search for ID numbers with 8 digits
#             id_numbers = re.findall(r'\b[01]\d{7}\b', text)

#             print(json.dumps({'id_numbers': id_numbers}))  # Print the output

#     except Exception as e:
#         print(f"Error: {e}")
#         print(json.dumps({'id_numbers': []}))  # Print an empty list in case of error

# if __name__ == "__main__":
#     image_path = sys.argv[1]  # Provide the image path as a command-line argument
#     extract_id_number(image_path)
