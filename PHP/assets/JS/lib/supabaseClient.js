import { createClient } from '@supabase/supabase-js'

const supabaseUrl = 'https://cwxdorskmuteodiiixts.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImN3eGRvcnNrbXV0ZW9kaWlpeHRzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDU0ODQ4NzAsImV4cCI6MjA2MTA2MDg3MH0.CcLms5Oh5HYpqVbsIe9WKZ8PN2jFGEPtzudJQ3liLA4';


export const supabase = createClient(supabaseUrl, supabaseKey)