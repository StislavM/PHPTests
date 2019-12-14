#!/usr/bin/env bash

input="./required_images.txt"
while IFS= read -r line
do
  docker pull $line
done < "$input"
