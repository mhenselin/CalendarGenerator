# CalendarGenerator
The goal is to have a printable PDF with customized calendar including german school holidays and/or public holidays


## Usage 
### Rendering the Calendar
``
bin/console calendar:generate
``

### Calendar events
To also render holidays you can fetch the dates for public holidays from "Deutsche Feiertage API"  https://deutsche-feiertage-api.de 

``
bin/console calendar:fetch:holidays
``