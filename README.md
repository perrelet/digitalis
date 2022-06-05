# Digitalis

Framework for Digitalis Web Design wordpress installs.

## Digitally Down to Earth

“Foxglove, Foxglove,  
What do you see?”  
The cool green woodland,  
The fat velvet bee;  
Hey, Mr Bumble,  
I’ve honey here for thee!  

“Foxglove, Foxglove,  
What see you now?”  
The soft summer moonlight  
On bracken, grass, and bough;  
And all the fairies dancing  
As only they know how.  

## Modules

The following modules are available out of the box, however each needs to be activated individually at `/wp-admin/options-general.php?page=digitalis`

**🧾 ACF: Show Names for Administrators**

*Prints field slugs next to their names for qucik reference.*

**🌈 ACF: Restyle fields**

*Overrides the default styling of all ACF fields for a modern experience.*

**🗼 ACF: Height option for wysiwyg fields**

*Adds an option to wysiwyg fields to set their height in the backend.*

**🔓 Create Site Admin Role**

*Adds a new role called 'Site Admin', which is identical to 'Administrator' except for limiting access to Oxygen Builder and ACF admin panels - Perfect for clients!*

**🤖 Debugging Settings**

*Allows errors to be displayed for admins only.*

**📈 Development Monitor**

*An automatic time tracking system for tracking development time on a per user basis.*

**🦶 Create Footer Widget Areas**

*Registers any number of widgets ready for use in the footer or sidebar.*

**👻 Hide WP Menu**

*Decultters the front end by hiding the wp admin bar unless its being hovered or viewed on mobile.*

**🖊️ Log JavaScript Errors**

*Sends front end js errors to a dedicated log file.*

**🔑 Customise the Login Page**

*Override the wordpress logo with a custom graphic on the login view.*

**🛠️ Maintenance Mode**

*Drop the site into maintenance mode for visitors.*

**🚀 Oxygen Templates as ACF locations**

*Select where fields appears based on the current oxygen template.*

**🎨 Oxygen global colors in Gutenberg**

*Get access to the glboal colors defined in oxygen builder directly in the Gutemberg editor*

**🧰 Oxygen organiser**

*Displays template inheritance in admin views and add notes to templates for referencing and better organisation.*

**🌍 Oxygen global variables in SCSS files.**

*Get access to global colors, fonts and breakpoints defined in oxygen builder directly in SCSS. (Requires [Sassy](Sassy))

**🕢 Page Loader**

*Display an overlay with a funky CSS loader while the dom loads.*

**👂 Add Classes to Body on Scroll**

*Adds `scrolled`, `scroll-down` and `scroll-up` classes to the document body as the user interacts with the page.*

**🖌️ Style the Scrollbars**

*Add CSS styles to the sites scrollbars*

**🖐 Welcome Message**

*Display a welcome message in the wp dashboard when the user logs in.*
## Shortcodes

### **metasvg**

Add an inline or embedded SVG for a given meta field.

| Attribute | Required | Default | Notes |
| - | - | - | - |
| url |  | !svg | URL of the SVG |
| inline |  | false | Embed type |
| id |  |  | Element ID |
| class |  |  | CSS classes |
| style |  |  | Inline styles |

### **metastyles**

Creates a series of inline classes for a given repeater field. This is useful for setting CSS properties such as the 'background-image' when using wrapper shortcodes such as metarepeat and metaunslider.

| Attribute | Required | Default | Notes |
| - | - | - | - |
| field | ✔️ |  | Repeater field. |
| subfield | ✔️ |  | Subfield of repeater to get CSS value from. |
| prop | ✔️ |  | Target CSS property. |
| select | |  | Name of child selector (example: '.class') |
| imp | |  | Force the styling. |

### **metavideo**

Add a youtube or vimeo video with its ID given by a field

| Attribute | Required | Default | Notes |
| - | - | - | - |
| field |  | video_id | Repeater field. |
| aspect |  | 16 / 9 | Aspect Ratio |
| settings |  |  | Video URL arguments |
| responsive |  | true | Responsive video. |
| background |  | false | Creates a fixed, full device width background video. Overrides responsive, and assumes autoplay, loop, modestbranding and no controls. |
| service |  | youtube | Video service: "youtube" or "vimeo" |

>**Youtube & Vimeo Settings:** `autoplay`, `controls`, `loop` <br>
>**Youtube Settings:** `modestbranding`, `disablekb`<br>
> Missing values are assumed to be '1'. Example:
`settings="autoplay, loop=1,modestbranding=1,controls=0"`

### **metavideos**

Creates a responsive video gallery. Requires a repeater field with title, ID and service subfields.

| Attribute | Required | Default | Notes |
| - | - | - | - |
| field |  | video_gallery | Repeater field. |
| id |  | video_id | Video ID subfield. |
| service |  | video_service | Video service subfield ("youtube" or "vimeo"). |
| aspect |  | 16 / 9 | Aspect ratio of videos. |
| columns |  | 3 | Max number of columns in gallery. (false = infinite) |
| margin |  | 10 | Video margins. |
| min-width |  | 300 | Video minimium width. |

## Credits

- Șandor Sergiu - [Vanilla Tilt](https://micku7zu.github.io/vanilla-tilt.js/)
- maxwellito - [Vivus JS](https://maxwellito.github.io/vivus/)
- Jhey Tompkins - [JS thottling & debouncing](https://codeburst.io/throttling-and-debouncing-in-javascript-b01cad5c8edf).

Written and maintained by Jamie Perrelet. 

![Digitalis](https://digitalisweb.ca/wp-content/plugins/digitalisweb/assets/png/logo/digitalis.222.250.png)
