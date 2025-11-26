import Facebook from './Facebook'
import Line from './Line'

const Features = {
    Facebook: Object.assign(Facebook, Facebook),
    Line: Object.assign(Line, Line),
}

export default Features