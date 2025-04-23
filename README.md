# Bowling-Game-Kata-PHP
TDD bowling game kata

# Rules

The game consists of 10 frames. In each frame the player has two rolls to knock down 10 pins. The score for the frame is the total number of pins knocked down, plus bonuses for strikes and spares.
A spare is when the player knocks down all 10 pins in two rolls. The bonus for that frame is the number of pins knocked down by the next roll.
A strike is when the player knocks down all 10 pins on his first roll. The frame is then completed with a single roll. The bonus for that frame is the value of the next two rolls.
In the tenth frame a player who rolls a spare or strike is allowed to roll the extra balls to complete the frame. However no more than three balls can be rolled in tenth frame.

# Requirements
Write a class `Game` that has two methods

`void roll(int)` is called each time the player rolls a ball. The argument is the number of pins knocked down.

`int score()` returns the total score for that game.

# Follow up
It has been added the [Robert C. Martin](BowlingGameKata.pdf) solution in the code base in pdf format 

# Development environment
- It requires `docker`
- Execute the following commands

```
$> make build
$> make up
$> make install 
```

- To execute the tests execute
```
$> make tests
```

- To generate the coverage at the `build/coverage` folder execute
```
$> make coverage
```

- The solution kata will be implemented and explained at the `README.md` file at the `solution` branch

# Solution
- The whole solution will be implemented following the `TDD` strategy
- For each implementation the 3 `TDD` steps will be explained in detail
    - `RED` -> fail, writing a failing test
    - `GREEN` -> fix, make the test pass quick
    - `BLUE` -> refactor, clean up the code and introduce any design if it is required
    - `NONE` -> refactor and fixes which are manly related to mistakes, findings or adding documentation out of the `TDD` process
- When it comes to the `BLUE` state, usually every small refactor that I do I commit the refactor to have a safe returning point. Nevertheless, in this exercise I will just go for a single commit for the `BLUE` refactor stage regardless how bit the refactor is. The steps will be documented at the `README.md` file for each iteration
- At every step the code coverage will be checked
- Let's start creating unit tests considering that the `Game` has a single frame and cover all the logic for a single frame
    - Edge cases
        - The `Game` does not accept negative `pins` for the `roll` method
            - `RED`: Write one unit test for the `roll` method passing negative `pins` value. Notice that the `roll` method does not even exist at this point
            - `GREEN`: Add method `roll` at `Game` class and throw an exception
            - `BLUE`: Nothing to refactor at this point. Let's continue :)
        - The `Game` allow to roll `1` `pin`
            - `RED`: write one unit test for the `roll` method passing `1` as num of `pins` parameter. Also the test would call to the not existent yet `score` method
            - `GREEN`: 
                - fix the test fast, adding a safe guard to check whether the `pins` is negative to throw the exception 
                - implement `score` method returning `1`
            - `BLUE`:
                - refactor `GameTest` to use `setUp` method to create the `Game` sut
                - implements early return at `Game roll` method in case positive or zero number
        - The `Game` does not allow more than `10` pins to be rolled
            - `RED`: Falling test try to `roll` more than `10` pins
            - `GREEN`: add to the safe guard the condition of less than `10` pins at the `roll` method
            - `BLUE`: 
                - refactor `Game roll` method to make to early returns when `pins` is negative number and when it is greater than `0`
                - refactor extract `pins` value into a variable in `GameTest` class
    - Return the real `score` value
        - `RED`: Failing test that when `roll` `2` pins `score` returns `1`
        - `GREEN`: Introduce `pinsRolled Game class field` to save the current `pins rolled` value
        - `BLUE`: Not further refactors have been identified in this iteration. Moving forward :D
    - Implements `spare` logic for a single frame
        - `RED`: failing unit test knocking down the 10 `pins` in 2 `rolls`, `score` should return `10` plus `3` which are the `pins` knock down into the next third `roll`
        - `GREEN`: sum the `pinsRolled` up
        - `BLUE`: 
            - refactor `GameTest` methods to add the `score` method name
            - refactor `Game` extracting guard logic into a method
    - Implements `spare` logic for a 2 frames
        - `RED`: failing unit test knocking down 10 `pins` in 2 `rolls`, `score` should return `10` plus the `pins` for next `frame` `3 + 3 = 6` plus `3` extra points for the `spare next roll`
        - `GREEN`: introduce array structure to store `frame` information 
            - take me a little bit more time than usual and it is not unfortunatelly a baby step. Nevertheless, I did not know how to do a shorter baby step
        - `BLUE`:
            - Turning `currentFrame` structure into integer and just use `frames` structure to store the `roll`
            - Return `reduce` response directly removing the `score` variable at `score` method
            - Update first `roll1` frame instead of checking every time the `currentFrame`
    - Implements `strike` logic
        - `RED`: failing tests that when in the first `roll` knock all pins down, the next 2 `rolls` should not just add up to the current `frame` but also to the previous one
        - `GREEN`: fix the test adding the `strike` logic at the `Game` class
        - `BLUE`:
            - refactor `score` anonymous function add `int` return type hint
            - refactor move `spare` logic into process `roll1` logic
            - refactor extract paragraph code into methods
                - extract first main `roll` logic into a method call `processFrame`
                - extract `roll1` and `roll2` logic into other methods
                - extract `processSpare` and `processStrike` into methods
    - Implements 10th frame logic
        - Implements when 11th `frame` without `spare` or `strike` has finished, no more rolls are allowed
            - `RED`: unit test that implements the logic of no more roll allowed at 11th frame without `spare` or `strike` on 10th frame
            - `GREEN`: fix at the `roll` method when `frame` is greather than 10, throw an exception
            - `BLUE`: refactor introduce `Frame` data transfer object and use it to update and gather frame data at `Game`
        - Implements when 11th `frame` with pending `spare` should allow one extra `roll`
            - `RED`: unit test that checks that an extra `roll` is allowed at 11th `frame` when there is a `pending` spare
            - `GREEN`: fix at `roll` method allow extra `roll` when there is a pending `spare`
            - `BLUE`:
                - refactor `GameTest` extract magic numbers into variables at multiple roll to improve readability
                - refactor `Game` extract `spare` logic detection into a method
                - refactor `Game` extract `strike` logic detection into a method
                - refactor move logic `Game` to `Frame` class to reduce `feature envy`
                    - move first the recent create `spare` and `strike` detection methods from `Game` class to `Frame` class
                        - move `spare` detection method to `Frame` and use it at `Game`
                        - inline `isTherePendingSpare` method at `Game` using new `Frame` method
                        - move `strike` detection method to `Frame` and use it at `Game`
                        - inline `isTherePendingStrike` method at `Game` using new `Frame` method
                        - Rename methods removing `TherePending` wording at method's name
                        - Implements `rollScore` method at `Frame` class and use it at `Game`
                        - Implements `totalScore` method at `Frame` class and use it at `Game` to calculate `score`
                        - Initialize first `Frame` when initialize `Game` and every time the `currentFrame` is incremented to be able to apply `tell do not ask` principles to the `Frame` methods
                        - Create `processSpare` method at `Frame` class and move the `Frame` logic from the `Game` class to this new `Frame` method
                        - Create `processStrike` method at `Frame` class and move the `Frame` logic from the `Game` class to this new `Frame` method
                        - apply safe guard early return for `processStrike` and `processSpare` methods at `Frame` class
                        - reduce `Frame bonus` field visibility to `private`
                    - replace variable last `Frame` with a query
                        - add method at `Game` class to retrieve the last `Frame` and inline variable where the last `Frame` is retrieved via array
                    - replace variable current `Frame` with a query
                        - add method at `Game` class to retrieve the current `Frame` and inline variable where the current `Frame` is retrieved via array
                    - refactor `Game` rename `lastFrame` method to `previousFrame`
                    - refactor `Game` extract create next and new `Frame` into methods
                        - extract current `Frame` creation into a method
                        - extract next `Frame` creation into a method
                    - add method to set previous frame
                    - add method to check whether the current frame is the first one at `Game`
                    - refactor `Game` extract magic numbers into constants
                        - extract minimum `pins` value into a constant
                        - extract maximum `pins` value into a constant
                        - extract maximum `frames` value into a constant
                    - refactor `Game` extract logic to allow `rolls`
                - At this point of the refactor most of the logic related to the `Frame` has been moved from the `Game` class to the `Frame` class, reducing the `feature envy` to the minimum
                - The `Game` is now just a mere `Frames` manager collection
                - In further iterations more refactor would be applied to add more logic to the `Frame` if that is posible :)
        - Implements 11th frame with pending `spare` should not allow more than one extra `roll`
            - `RED`: failing test that at 11th frame try to `roll` twice, game should throw an exception
            - `GREEN`: fix the test adding logic at the `Game` class more specifically at the `isRollAllowed` method
                - introduce `lastExtraFrame` game field variable
            - `BLUE`:
                - Refactor `GameTest` to extract multiple game for loop into a method
                - Refactor `Game` encapsulate last frame processing logic into a method
                - Refactor add method at `Frame` to check whether `isFirstRoll`
                - refactor `Frame` add `processFirstRoll` method to update first roll value
                - refactor `Frame` add `processSecondRoll` method to update first roll value 
                - refactor `Frame` reduce all fields visibility to `private`
                - refactor rename `Frame` fields to `firstRoll` and `secondRoll` 
                - refactor `Game` add extra method to update `currentFrame`
        - Implements when 11th `frame` with `strike` at the last frame, should allow one more `roll`
            - `RED`: failing test when last frame has a pending `strike` to be processed, it should allow an extra roll. Right now just the `spare` logic is considered to allow the roll. Therefore, at this point the test throws an expected error
            - `GREEN`: fix the test extending allow roll logic to `strike`
