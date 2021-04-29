# Python script to use the TensorFlow model to return a list of directions based on the Users ingredients (given as input)

import os, sys, json, random
import numpy as np
# Turn off debugging messages and import TensorFlow
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '3'
import tensorflow as tf

# Method to more more naturally select the next best word
def sample(predictions, temperature):
    exp_preds = np.exp(
        np.log(np.asarray(predictions).astype('float64')) / temperature
    )
    predictions = exp_preds / np.sum(exp_preds)
    return np.argmax(np.random.multinomial(1, predictions, 1))

# Method to generate directions based on the input ingredient
def generate_direction(model, ingredient, num_generate, temperature):
    # Create the string to hold the seed text
    direction = tokenizer.texts_to_sequences([ingredient])
    # Loop through the number of words to generate
    for _ in range(num_generate):
        # Generate the tokenised input sequence
        token_list = tf.keras.preprocessing.sequence.pad_sequences(
            direction, maxlen=211, padding='pre'
        )
        # Get the prediction matrix for "next possible words" from the model
        predictions = model.predict(token_list, verbose=0)[-1]
        # Add the next "best" word from sample() to the direction token array
        direction.append([sample(predictions, temperature)])

    # Return the fully generated direction string
    return ' '.join(tokenizer.sequences_to_texts(direction))


# 'Main' method
if __name__ == '__main__':

    # Load the data that the MLContainer provided
    try:
        # Get the input data (and remove backslash formatting)
        input_data = sys.argv[1].replace('\\','')
        # Load the data as json
        data = json.loads(input_data)
    except Exception as e:
        print(type(e))
        sys.exit(1)

    # Load the TensorFlow model
    model = tf.keras.models.load_model(os.path.join(sys.path[0], 'saved_model/ReInsGen'))
    # Load the tokenizer from stored JSON file
    tokenizer = tf.keras.preprocessing.text.tokenizer_from_json(json.load(open(os.path.join(sys.path[0], 'tokenizer.json'), 'rb')))

    # Empty list to contain the generated directions
    directions = []

    # Generate a direction based on the ingredient given
    for ingredient in input_data:
        directions.append(generate_direction(model, ingredient, 30, 0.33))

    # Return the directions to the MLContainer
    print(directions)
