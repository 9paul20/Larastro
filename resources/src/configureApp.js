import PrimeVue from 'primevue/config';
import App from '@src/views/App.vue';
import router from '@src/layouts/router';
//
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import AutoComplete from 'primevue/autocomplete';
import Avatar from 'primevue/avatar';
import AvatarGroup from 'primevue/avatargroup';
import Badge from 'primevue/badge';
import BadgeDirective from 'primevue/badgedirective';
import BlockUI from 'primevue/blockui';
import Breadcrumb from 'primevue/breadcrumb';
import Button from 'primevue/button';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Carousel from 'primevue/carousel';
import CascadeSelect from 'primevue/cascadeselect';
import Chart from 'primevue/chart';
import Checkbox from 'primevue/checkbox';
import Chip from 'primevue/chip';
import Chips from 'primevue/chips';
import ColorPicker from 'primevue/colorpicker';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import ConfirmDialog from 'primevue/confirmdialog';
import ConfirmPopup from 'primevue/confirmpopup';
import ContextMenu from 'primevue/contextmenu';
import DataTable from 'primevue/datatable';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions';
import DeferredContent from 'primevue/deferredcontent';
import Dialog from 'primevue/dialog';
import Divider from 'primevue/divider';
import Dock from 'primevue/dock';
import Dropdown from 'primevue/dropdown';
import DynamicDialog from 'primevue/dynamicdialog';
import Editor from 'primevue/editor';
import Fieldset from 'primevue/fieldset';
import FileUpload from 'primevue/fileupload';
import FocusTrap from 'primevue/focustrap';
import Galleria from 'primevue/galleria';
import Image from 'primevue/image';
import InlineMessage from 'primevue/inlinemessage';
import Inplace from 'primevue/inplace';
import InputMask from 'primevue/inputmask';
import InputNumber from 'primevue/inputnumber';
import InputSwitch from 'primevue/inputswitch';
import InputText from 'primevue/inputtext';
import Knob from 'primevue/knob';
import Listbox from 'primevue/listbox';
import MegaMenu from 'primevue/megamenu';
import Menu from 'primevue/menu';
import Menubar from 'primevue/menubar';
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';
import OrderList from 'primevue/orderlist';
import OrganizationChart from 'primevue/organizationchart';
import OverlayPanel from 'primevue/overlaypanel';
import Paginator from 'primevue/paginator';
import Panel from 'primevue/panel';
import PanelMenu from 'primevue/panelmenu';
import Password from 'primevue/password';
import PickList from 'primevue/picklist';
import ProgressBar from 'primevue/progressbar';
import ProgressSpinner from 'primevue/progressspinner';
import RadioButton from 'primevue/radiobutton';
import Rating from 'primevue/rating';
import Ripple from 'primevue/ripple';
import Row from 'primevue/row';
import ScrollPanel from 'primevue/scrollpanel';
import ScrollTop from 'primevue/scrolltop';
import SelectButton from 'primevue/selectbutton';
import Sidebar from 'primevue/sidebar';
import Skeleton from 'primevue/skeleton';
import Slider from 'primevue/slider';
import SpeedDial from 'primevue/speeddial';
import SplitButton from 'primevue/splitbutton';
import Splitter from 'primevue/splitter';
import SplitterPanel from 'primevue/splitterpanel';
import Steps from 'primevue/steps';
import StyleClass from 'primevue/styleclass';
import TabMenu from 'primevue/tabmenu';
import TabPanel from 'primevue/tabpanel';
import TabView from 'primevue/tabview';
import Tag from 'primevue/tag';
import Terminal from 'primevue/terminal';
import TerminalService from 'primevue/terminalservice';
import Textarea from 'primevue/textarea';
import TieredMenu from 'primevue/tieredmenu';
import Timeline from 'primevue/timeline';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';
import ToggleButton from 'primevue/togglebutton';
import Toolbar from 'primevue/toolbar';
import Tooltip from 'primevue/tooltip';
import Tree from 'primevue/tree';
import TreeSelect from 'primevue/treeselect';
import TreeTable from 'primevue/treetable';
import TriStateCheckbox from 'primevue/tristatecheckbox';
import VirtualScroller from 'primevue/virtualscroller';
//
const components = {
  PrimeVue,
  App,
  Accordion,
  AccordionTab,
  AutoComplete,
  Avatar,
  AvatarGroup,
  Badge,
  BlockUI,
  Breadcrumb,
  Button,
  Calendar,
  Card,
  Carousel,
  CascadeSelect,
  Chart,
  Checkbox,
  Chip,
  Chips,
  ColorPicker,
  Column,
  ColumnGroup,
  ConfirmDialog,
  ConfirmPopup,
  ContextMenu,
  DataTable,
  DataView,
  DataViewLayoutOptions,
  DeferredContent,
  Dialog,
  Divider,
  Dock,
  Dropdown,
  DynamicDialog,
  Editor,
  Fieldset,
  FileUpload,
  Galleria,
  Image,
  InlineMessage,
  Inplace,
  InputMask,
  InputNumber,
  InputSwitch,
  InputText,
  Knob,
  Listbox,
  MegaMenu,
  Menu,
  Menubar,
  Message,
  MultiSelect,
  OrderList,
  OrganizationChart,
  OverlayPanel,
  Paginator,
  Panel,
  PanelMenu,
  Password,
  PickList,
  ProgressBar,
  ProgressSpinner,
  RadioButton,
  Rating,
  Row,
  ScrollPanel,
  ScrollTop,
  SelectButton,
  Sidebar,
  Skeleton,
  Slider,
  SpeedDial,
  SplitButton,
  Splitter,
  SplitterPanel,
  Steps,
  TabMenu,
  TabPanel,
  TabView,
  Tag,
  Terminal,
  TerminalService,
  Textarea,
  TieredMenu,
  Timeline,
  Toast,
  ToggleButton,
  Toolbar,
  Tree,
  TreeSelect,
  TreeTable,
  TriStateCheckbox,
  VirtualScroller,
};
const directives = [
  { name: "badge", directive: BadgeDirective },
  { name: "focustrap", directive: FocusTrap },
  { name: "tooltip", directive: Tooltip },
  { name: "ripple", directive: Ripple },
  { name: "styleclass", directive: StyleClass },
];
const uses = [
  { name: PrimeVue, parameter: { inputStyle: "filled", ripple: true } },
  { name: router, parameter: null },
  { name: ToastService, parameter: null },
];
//
export default { components, directives, uses };