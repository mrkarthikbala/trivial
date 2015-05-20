import datetime, json, os, sys, csv, pyPdf, re
from random import randint

def get_json_path(filename):
    return "{0}/{1}".format("jsons",filename)

def get_csv_path(filename):
    return "{0}/{1}".format("csvs",filename)

def get_pdf_path(filename):
    return "{0}/{1}".format("pdfs", filename)

def load_json(path):
    if os.path.exists(path):
        json_data=open(path)
        data = json.load(json_data)
        json_data.close()
        return data
    return {}

def save_json(dictionary, path):
    directory = ""
    folders = path.split('/')
    for i in range(len(folders)-1):
        directory += folders[i]+"/"
    if not os.path.isdir(directory):
        print "'{0}'' is not a directory, so I am making it ".format(directory)
        os.mkdir(directory)
    with open (path,'w') as f:
        json.dump(dictionary, f, indent=2)

def make_csv(list_of_lists, path):
    with open(path, 'wb') as csvfile:
        spamwriter = csv.writer(csvfile, delimiter=',',quotechar=' ', quoting=csv.QUOTE_MINIMAL)
    for row in list_of_lists:
        spamwriter.writerow(row)

def get_pdf_as_string(path):
    content = ""
    # Load PDF into pyPDF
    pdf = pyPdf.PdfFileReader(file(path, "rb"))
    # Iterate pages
    for i in range(0, pdf.getNumPages()):
        # Extract text from page and add to content
        content += pdf.getPage(i).extractText() + "\n"
    # Collapse whitespace
    content = " ".join(content.replace(u"\xa0", " ").strip().split())
    content = content.encode('utf-8')
    return content

def cleanse(content):
    regex = re.compile('High School Round [0-9]+ Page [0-9]+')
    return re.sub(regex, '', content)

def get_questions(content):
    match = re.compile("-UP [0-9]+(.*?)TOSS")
    return re.findall(match, content)

def join_range(start, end, words):
    retval = ""
    for i in range(start,end):
        retval = " ".join([retval, words[i]])
    return retval

def get_word_index(needle, haystack):
    for i in range(len(haystack)):
        if needle in haystack[i]:
            return i

def sub_question_to_json(sub_question):
    # THERE IS SOME STRAY UNICODE WHITESPACE CHARACTER I SHOUDL REMOVE
    json = {}
    words = sub_question.split()
    subject_length = 0
    while words[subject_length] == words[subject_length].upper():
        subject_length += 1
    json['subject'] = join_range(0, subject_length, words)
    json['type'] = join_range(subject_length, subject_length+2, words)
    answer_index = get_word_index("ANSWER:", words)
    json['question'] = join_range(subject_length+2,answer_index, words)
    if "Short Answer" in json['type']:
        json['answer'] = join_range(answer_index+1, len(words), words)
    else:
        w_index = get_word_index("W)", words)
        x_index = get_word_index("X)", words)
        y_index = get_word_index("Y)", words)
        z_index = get_word_index("Z)", words)
        json['W'] = join_range(w_index+1, x_index, words)
        json['X'] = join_range(x_index+1, y_index, words)
        json['Y'] = join_range(y_index+1, z_index, words)
        json['Z'] = join_range(z_index+1, answer_index, words)
        json['answer'] = words[answer_index+1].replace(")","")
    return json

def print_question(question):
    tossup = question['tossup']
    print "tossup", tossup['subject'], tossup['type']
    print "\t"+tossup['question']
    if "Multiple Choice" in tossup['type']:
        print "\t\tW)", tossup['W']
        print "\t\tX)", tossup['X']
        print "\t\tY)", tossup['Y']
        print "\t\tZ)", tossup['Z']
    print "\tAnswer: "+ tossup['answer']
    print ""
    bonus = question['bonus']
    print "bonus", bonus['subject'], bonus['type']
    print "\t"+bonus['question']
    if "Multiple Choice" in bonus['type']:
        print "\t\tW)", bonus['W']
        print "\t\tX)", bonus['X']
        print "\t\tY)", bonus['Y']
        print "\t\tZ)", bonus['Z']
    print "\tAnswer: "+ bonus['answer']
    print "______________________________________________________"

def question_to_json(question):
    question = question[2:]
    regex = re.compile('BONUS [0-9]+')
    tossup_bonus = re.split(regex, question)
    tossup = tossup_bonus[0]
    bonus = tossup_bonus[1][2:]
    json = {}
    json['tossup'] = sub_question_to_json(tossup)
    json['bonus'] = sub_question_to_json(bonus)
    
    return json

def get_round_json(filename):
    json = []
    path = get_pdf_path(filename)
    pdf_content = get_pdf_as_string(path)
    pdf_content = cleanse(pdf_content)
    questions = get_questions(pdf_content)
    i = 0
    for question in questions:
        # print "working on question {0}".format(i)
        try:
            question_json = question_to_json(question)
            json.append(question_json)
        except:
            "Poorly Formated Question... Skipping..."
        i+=1
    return json

def get_short_answer_query(json):

    return "INSERT INTO SAQuestions(question, answer, difficulty, subject) VALUES({0}, {1}, {2}, {3});".format("'"+ json['question']+"'", "'"+json['answer'][1:]+"'", str(randint(1, 1000)), "'"+json['subject']+"'")

def get_multiple_choice_query(json):
    return "INSERT INTO MCQuestions(answer, choiceA, choiceB, choiceC, choiceD, question, difficulty, subject) VALUES ({0}, {1} , {2}, {3}, {4}, {5}, {6}, {7});".format("'" + json['answer']+"'" ,"'" +json['W']+"'" ,"'" +json['X']+"'" ,"'" +json['Y']+"'" ,"'" +json['Z']+"'" ,"'" +json['question']+"'" ,str(randint(1,1000)),"'" +json['subject']+"'" )

def get_subquestion_query(json):
    if "Short Answer" in json['type']:
        return get_short_answer_query(json)
    else:
        return get_multiple_choice_query(json)

def get_question_query(json):
    return "{0}\n{1}".format(get_subquestion_query(json['tossup']),get_subquestion_query(json['bonus']))

def main():
    pdfs = os.listdir("pdfs")
    # round_json = get_round_json(pdfs[0])
    # for json in round_json:
    #     print get_question_query(json)

    with open("queries.txt", "w") as myfile:
        for pdf in pdfs:
            print "working on {0}".format(pdf)
            round_json = get_round_json(pdf)
            for json in round_json:
                myfile.write(get_question_query(json))
main()