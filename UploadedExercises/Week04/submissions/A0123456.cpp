#include <iostream>
using namespace std;
#include <iterator>
#include <vector>
#include <algorithm>
#include <string>
#include <fstream>

int main(int argc, const char* argv[])
{
    /*
    Design, write and test a C++ program that will read two command-line
   parameters as input. The first must be the name of a textfile, and 
   the second must be a positive integer. The textfile contains an arbitrary
   number of lines, with one word on each line, starting at the left margin.
   The positive integer indicates how many words are to be chosen at random
   from all the words in the file (assume it is <= the number of lines in
   the file. The program must read in the file, randomly choose the required
   number of words from the file, and then print out those words, one per
   line, ordered by word length (shortest to longest).
    */
    ifstream inFile(argv[0]);
    unsigned int wordCount = atoi(argv[1]);
    vector<string> allWords;
    copy(istream_iterator<string>(inFile), istream_iterator<string>(),back_inserter(allWords));
    random_shuffle(allWords.begin(), allWords.end());
    vector<string> randomWords(allWords.begin(),allWords.begin()+wordCount);
    sort(randomWords.begin(), randomWords.end(), [](string s1, string s2) {return s1.length() < s2.length();} );
    copy(randomWords.begin(),randomWords.end(),ostream_iterator<string>(cout,"\n"));
}