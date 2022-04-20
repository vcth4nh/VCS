import string

ALLOWED_CHARACTERS = string.ascii_letters + string.digits + string.punctuation
ALLOWED_CHARACTERS = string.ascii_lowercase + string.digits
NUMBER_OF_CHARACTERS = len(ALLOWED_CHARACTERS)


def character_to_index(char):
    return ALLOWED_CHARACTERS.index(char)


def index_to_character(index):
    if NUMBER_OF_CHARACTERS <= index:
        raise ValueError("Index out of range.")
    else:
        return ALLOWED_CHARACTERS[index]


def func_next_char(char):
    cur_char_idx = character_to_index(char)
    next_char = index_to_character((cur_char_idx + 1) % NUMBER_OF_CHARACTERS)
    return next_char


def bf_next_char(string):
    change_pos = -1
    string = list(string)
    if len(string) <= 0:
        string.append(index_to_character(0))
    else:
        next_char = func_next_char(string[change_pos])
        if next_char == ALLOWED_CHARACTERS[0]:
            while len(string) + change_pos >= 0:
                if string[change_pos] == ALLOWED_CHARACTERS[-1]:
                    string[change_pos] = ALLOWED_CHARACTERS[0]
                    change_pos -= 1
                else:
                    string[change_pos] = func_next_char(string[change_pos])
                    break
            if len(string) + change_pos < 0:
                string.append(next_char)
        else:
            string[change_pos] = next_char
    return ''.join(string)


def main():
    sequence = ''
    i = 0
    print(ALLOWED_CHARACTERS)
    while len(sequence) <= 3:
        i += 1
        sequence = bf_next_char(sequence)
        print(sequence)


if __name__ == "__main__":
    main()
