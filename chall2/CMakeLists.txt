cmake_minimum_required(VERSION 3.20)
project(my_project)
set(CMAKE_CXX_STANDARD 14)

set(SOURCE_FILES mypasswd.c)

# Add executable target with source files listed in SOURCE_FILES variable
add_executable(my_project  ${SOURCE_FILES})
