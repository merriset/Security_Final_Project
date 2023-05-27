#include <stdio.h>
#include <stdlib.h>


int main() {

    system("openssl genrsa -out private.pem");
    system("openssl rsa -pubout -in private.pem -out public.pem");

    return 0;
}