#include <iostream>
using namespace std;

int doStuff(int a, int b);

int main(int argc, char * argv[])
{
    cout << doStuff(3,5);
}

#include "includedFile.cpp"
