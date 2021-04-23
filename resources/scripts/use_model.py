# Python script to use the TensorFlow model to return a list of possible recipes based on the Users ingredients (given as input)

# Returned recipes don't take into account User preferences/allergies/etc so that will be done after calling the model (here too???)

import sys, json, os
# Turn off debugging messages
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
# Import tensorflow
import tensorflow as tf


# Method to generate directions based on the input ingredient
def generate_direction(model, ingredient, num_generate=30, temperature=1.0):
    # Create the string to hold the seed text
    direction = ''
    # Loop through the number of words to generate
    for _ in range(num_generate):
        # Generate the tokenised input sequence
        token_list = tokenizer.texts_to_sequences([ingredient])[0]
        token_list = tf.keras.preprocessing.sequence.pad_sequences(
            ingredient,
            maxlen=212,
            padding='pre'
        )
        # Get the prediction from the model
        predicted = model.predict_classes(token_list, verbose=0)

        # Ensure the predicted number is in the vocabulary
        if predicted in tokeniser.word_index.items():
            # If so, add the word mapping to the seed_text
            direction += ' '+inverse_vocab[predicted]

    # Return the fully generated direction
    return direction


# 'Main' method
if __name__ == '__main__':

    # Create the tokenizer
    tokenizer = tf.keras.preprocessing.text.Tokenizer()

    # Load the TensorFlow model
    model = tf.keras.models.load_model('ReInsGen.hd5')

    # Load the data that the MLContainer provided
    try:
        # Get the input data (and remove backslash formatting)
        input_data = sys.argv[1].replace('\\','')
        # Load the data as json
        data = json.loads(input_data)
    except Exception as e:
        print(type(e))
        sys.exit(1)

    # Empty list to contain the generated directions
    directions = []

    # Generate a direction based on the ingredient given
    for ingredient in inputs:
        directions.append(generate_direction(model, ingredient, 30, 1.0))

    # Return the directions to the MLContainer
    print(directions)
