/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

/**
 * Internal dependencies
 */
import { edit } from './edit';

export const name  = 'wordcamp/organizers';
export const LABEL = __( 'Organizers', 'wordcamporg' );
export const ICON  = 'groups';

const supports = {
	align: [ 'wide', 'full' ],
};

export const settings = {
	title       : __( 'Organizers',                'wordcamporg' ),
	description : __( 'Add a list of organizers.', 'wordcamporg' ),
	icon        : ICON,
	category    : 'wordcamp',
	supports    : supports,
	edit        : edit,
	save        : () => null,
};
