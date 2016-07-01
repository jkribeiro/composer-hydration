#!/bin/bash
# @file
# Script to be executed during the 'script' build step.

export BUILD_RESULT=0

# Colors
export RED=$'\E[1;31m'
export GREEN=$'\E[1;32m'
export BLUE=$'\E[1;34m'

# Message status.
export COLOR_RESET=$'\E[0m'
export MSG_ERROR="${RED}[TEST][ERROR]${COLOR_RESET}"
export MSG_OK="${GREEN}[TEST][OK]${COLOR_RESET}"
export MSG_INFO="${BLUE}[TEST][INFO]${COLOR_RESET}"

# Check by FOLDER replacement.
export FOLDER_NAME_HYDRATED="replacedfolder"
if [ -d "$FOLDER_NAME_HYDRATED" ]; then
  echo "$MSG_OK Folder Hydrated: '$FOLDER_NAME_HYDRATED'"
else
  echo "$MSG_ERROR Unable to rename a Folder: '$FOLDER_NAME_HYDRATED'"

  # Failing build.
  export BUILD_RESULT=1
fi

# Check by FILE replacement.
export FILE_NAME_HYDRATED="replacedfile"
if [[ ! -n $( find -L . -name "${FILE_NAME_HYDRATED}" ) ]]; then
  echo "$MSG_OK File Hydrated: '$FILE_NAME_HYDRATED.txt'"
else
  echo "$MSG_ERROR Unable to rename a File: '$FILE_NAME_HYDRATED.txt'"

  # Failing build.
  export BUILD_RESULT=1
fi

# TODO: Create a generic function to the tests below.
# Check by STRING replacement.
export STRING_HYDRATED="replacedstring"
if grep -q "$STRING_HYDRATED" "$FOLDER_NAME_HYDRATED" -r; then
  echo "$MSG_OK String Hydrated: '$STRING_HYDRATED'"
else
  echo "$MSG_ERROR Unable to replace content on file.: '$STRING_HYDRATED'"

  # Failing build.
  export BUILD_RESULT=1
fi

# Check by BASENAME replacement.
export BASENAME_HYDRATED="test"
if grep -q "$BASENAME_HYDRATED" "$FOLDER_NAME_HYDRATED" -r; then
  echo "$MSG_OK Magic contant {%BASENAME%} Hydrated: '$BASENAME_HYDRATED'"
else
  echo "$MSG_ERROR Unable to use Magic constant {%BASENAME%}: '$BASENAME_HYDRATED'"

  # Failing build.
  export BUILD_RESULT=1
fi

# Check by UCFIRST_BASENAME replacement.
export UCFIRST_BASENAME_HYDRATED="Test"
if grep -q $UCFIRST_BASENAME_HYDRATED "$FOLDER_NAME_HYDRATED" -r; then
  echo "$MSG_OK Magic contant {%UCFIRST_BASENAME%} Hydrated: '$UCFIRST_BASENAME_HYDRATED'"
else
  echo "$MSG_ERROR Unable to use Magic constant {%UCFIRST_BASENAME%}: '$UCFIRST_BASENAME_HYDRATED'"

  # Failing build.
  export BUILD_RESULT=1
fi

exit "$BUILD_RESULT"
